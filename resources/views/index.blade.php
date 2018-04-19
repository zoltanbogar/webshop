@extends('layouts.master')
@section('title')
	BigFish
@endsection

@section('content')
	@foreach($objProducts->chunk(3) as $rowProduct)
		<div class="row mb10px">
			@foreach($rowProduct as $product)
				<div class="col-sm-6 col-md-4 bordered_element">
					<div class="thumbnail">
						<img src="{{ URL::to($product->image) }}" class="img-responsive">
						<div class="caption">
							<h3>{{ $product->title }}</h3>
							<p class="product_description">
								{{ $product->author }}
							</p>
							<p class="product_description">
								{{ $product->publisher }}
							</p>
							<div class="clearfix">
								<div class="float-left">
									@if($product->discounted_price)
										<del>{{ $product->price }}</del> <span>{{ $product->discounted_price }}</span> {{ config('constants.currency') }}
									@else
										{{ $product->price }} {{ config('constants.currency') }}
									@endif
								</div>
								@if(Auth::check())
									<button class="btn btn-success float-right" onclick="addToCart({{ $product->id }})">Add to cart</button>
								@endif
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endforeach
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ URL::asset('js/index/index.js') }}"></script>
@endsection