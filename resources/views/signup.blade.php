@extends('layouts.master')

@section('title')
	Sign Up
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h1>
				Sign Up
			</h1>
			<div class="messageContainer"></div>
			<div id="signupForm">
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="form-group">
					<label for="email">E-Mail</label>
					<input type="text" name="email" class="form-control">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control">
				</div>
				<button type="submit" class="btn btn-success" onclick="signUp()">Sign Up</button>
				{{ csrf_field() }}
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ URL::asset('js/misc/messageHandler.js') }}"></script>
	
	<script>
		function signUp(){
			var strName = $('[name="name"]').val();
			var strEmail = $('[name="email"]').val();
			var strPassword = $('[name="password"]').val();

			$.ajax({
				type: "POST"
				, url: "/signup"
				, data: {
					name: strName
					, email: strEmail
					, password: strPassword
					, _token: '{{csrf_token()}}'
				}
				, dataType: "json"
				, success: function (tblResult) {
					handleSuccess(tblResult);
					if(typeof tblResult.strURL != "undefined"){
						window.location.replace(tblResult.strURL);
					}
				}
				, error: function(objXHR, tblResult) {
					handleError(objXHR);
				}
			});
		}
	</script>
@endsection