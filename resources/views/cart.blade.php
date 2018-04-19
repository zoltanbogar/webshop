@extends('layouts.master')
@section('title')
	BigFish
@endsection

@section('content')
	@if(Session::has('cart'))
		<div class="row product_container">
			<div class="col-sm-12 col-md-12 cold-md-offset-6 col-sm-offset-6">
				<ul class="list-group">
					@foreach($tblProducts as $numProductID => $rowProduct)
						<li class="list-group-item" product_id="{{ $numProductID }}">
							<div>Title: <strong>{{ $rowProduct['objItem']['title'] }}</strong></div>
							<div>Author: <em>{{ $rowProduct['objItem']['author'] }}</em></div>
							<span class="label label-success"></span>
							<div class="button_container">
								<input type="number" disabled="disabled" class="form-control input_spinner" name="quantity" min="1" value="{{ $rowProduct['numQuantity'] }}" latest_value="{{ $rowProduct['numQuantity'] }}">
								<button class="btn btn-danger" onclick="deleteProductFromCart({{ $numProductID }})">Delete</button>
							</div>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="row mt20px cart_price_container">
			<div class="col-sm-6 col-md-6 cold-md-offset-3 col-sm-offset-3">
				@if($numTotalPrice != $numDiscountedPrice)
					<div><del>Original price: <span class="cart_total_price">{{$numTotalPrice}}</span> {{ config('constants.currency') }}</del></div>
					<div>Amount of discounts: {{$numDiscountFromTotalPrice}} {{ config('constants.currency') }}</div>
					<strong>New price: <span class="cart_discounted_total_price">{{ $numDiscountedPrice }}</span> {{ config('constants.currency') }}</strong>
				@else
					<strong>Total: <span class="cart_total_price">{{ $numTotalPrice }}</span> {{ config('constants.currency') }}</strong>
				@endif
			</div>
		</div>
		<hr>
		<div class="row cart_save_container">
			<div class="col-sm-6 col-md-6 cold-md-offset-3 col-sm-offset-3">
				<button type="button" class="btn btn-success" onclick="saveCart()">Save</button>
			</div>
		</div>
	@else
		<div class="row">
			<div class="col-sm-6 col-md-6 cold-md-offset-3 col-sm-offset-3">
				<h2>No items in cart!</h2>
			</div>
		</div>
	@endif
@endsection
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script>
	var objTblProducts = {!! str_replace("'", "\'", json_encode($tblProducts)) !!};

	function saveCart(){
		console.log(Adjust.objTblProducts);
		$.ajax({
			 type: "POST"
			, url: "/saveCart"
			, data: {
				objCart: Adjust.objTblProducts
				, _token: '{{csrf_token()}}'
			}
			, dataType: "json"
			, success: function (tblResult) {
				if(typeof tblResult.strURL != "undefined"){
					window.location.replace(tblResult.strURL);
				}
			}
			, error: function(objXHR, tblResult) {
				console.log(objXHR, tblResult);
			}
		});
	}
</script>

<script type="text/javascript" src="{{ URL::asset('js/cart/adjust.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/cart/cart.js') }}"></script>