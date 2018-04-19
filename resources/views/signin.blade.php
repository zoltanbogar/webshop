@extends('layouts.master')

@section('title')
	Sign Up
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h1>
				Sign In
			</h1>
			<div class="messageContainer"></div>
			<div id="signupForm">
				<div class="form-group">
					<label for="email">E-Mail</label>
					<input type="text" name="email" class="form-control">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control">
				</div>
				<button type="submit" class="btn btn-success" onclick="signIn()">Sign In</button>
				{{ csrf_field() }}
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ URL::asset('js/misc/messageHandler.js') }}"></script>

	<script>
		function signIn(){
			var strEmail = $('[name="email"]').val();
			var strPassword = $('[name="password"]').val();

			$.ajax({
				 type: "POST"
				, url: "/signin"
				, data: {
					email: strEmail
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
					console.log(objXHR, tblResult);
				}
			});
		}
	</script>
@endsection