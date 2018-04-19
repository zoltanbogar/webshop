<nav class="navbar navbar-expand-lg navbar-light bg-light clearfix">
	<a class="navbar-brand" href="{{ route('product.index') }}">BigFish</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto">
			@if(Route::currentRouteName() == "product.cart")
				<li class="nav-item">
					<a class="nav-link" href="{{ route('product.cart') }}">
						<i class="fas fa-shopping-cart"></i> Cart 
						<span class="badge badge-info cart_badge">{{ Session::has('cart') ? Session::get('cart')->numTotalQuantity : '' }}</span>
					</a>
				</li>
			@else
				<li class="nav-item dropdown cart_dropdown_menu_item">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCart" role="button" data-toggle="/*dropdown*/" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-shopping-cart"></i> Cart 
						<span class="badge badge-info cart_badge">{{ Session::has('cart') ? Session::get('cart')->numTotalQuantity : '' }}</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<div class="dropdown-divider"></div>
						<a class="nav-link" href="{{ route('product.cart') }}">Open Cart</a>
					</div>
				</li>
			@endif
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				 <i class="fas fa-user"></i> User
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					@if(Auth::check())
						<a class="dropdown-item" href="{{ route('user.logout') }}">Log Out</a>
					@else
						<a class="dropdown-item" href="{{ route('user.signup') }}">Sign Up</a>
						<a class="dropdown-item" href="{{ route('user.signin') }}">Sign In</a>
					@endif
				</div>
			</li>
		</ul>
	</div>
</nav>

<script type="text/javascript" src="{{ URL::asset('js/misc/header.js') }}"></script>
<script>
	function addDataToCartBoxList(tblResult){
		$('.cart_added_products').remove();

		var contentList = '';
		$.each(tblResult.tblProducts, function(numProductID, objProduct){
			contentList += '' +
			'	<div class="dropdown-item cart_added_products" product_id="' + objProduct.objItem.id + '">' +
			'		' + objProduct.objItem.title +
			'		<span class="badge badge-info">' + objProduct.numQuantity + '</span>' +
			'	</div>';
		});

		if(tblResult.numTotalPrice != tblResult.numDiscountedPrice){
			var contentPrices = '' +
			'<div class="cart_added_products">' +
			'	<hr>' +
			'	<div>Total:</div>' +
			'	<div class="dropdown-item">' +
			'		<del>' + tblResult.numTotalPrice + ' {{ config('constants.currency') }} </del>' +
			'	</div>' +
			'	<div class="dropdown-item">' +
					tblResult.numDiscountedPrice + ' {{ config('constants.currency') }}' +
			'	</div>' +
			'</div>';
		} else {
			var contentPrices = '' +
			'<div class="cart_added_products">' +
			'	<hr>' +
			'	<div>Total:</div>' +
			'	<div class="dropdown-item">' +
					tblResult.numTotalPrice + ' {{ config('constants.currency') }}' +
			'	</div>' +
			'</div>';
		}

		$('li.cart_dropdown_menu_item')
		.children('div.dropdown-menu')
		.children('div.dropdown-divider')
		.before(contentList + contentPrices);

		$('li.cart_dropdown_menu_item').addClass('show');
		if(typeof tblResult.numTotalPrice != "undefined")
			$('li.cart_dropdown_menu_item').children('div').addClass('show');
	}
</script>
