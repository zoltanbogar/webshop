<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
	'uses' => 'ProductController@getIndex',
	'as' => 'product.index'
]);

Route::group(['middleware' => 'guest'], function(){
	Route::get('/signup', [
		'uses' => 'UserController@getSignup',
		'as' => "user.signup"
	]);

	Route::post('/signup', [
		'uses' => 'UserController@postSignup',
		'as' => "user.signup"
	]);

	Route::get('/signin', [
		'uses' => 'UserController@getSignin',
		'as' => "user.signin"
	]);

	Route::get('/login', [
		'uses' => 'UserController@getSignin',
		'as' => "login"
	]);

	Route::post('/signin', [
		'uses' => 'UserController@postSignin',
		'as' => "user.signin"
	]);
});

Route::group(['middleware' => 'auth'], function(){
	Route::get('/logout', [
		'uses' => 'UserController@getLogout',
		'as' => "user.logout"
	]);

	Route::get('/add-to-cart/{numID}', [
		'uses' => 'ProductController@getAddToCart',
		'as' => 'product.addToCart'
	]);

	Route::get('/cart', [
		'uses' => 'ProductController@getCart',
		'as' => 'product.cart'
	]);
	Route::get('/cart-box', [
		'uses' => 'ProductController@getCartBox',
		'as' => 'product.cart-box'
	]);

	Route::post('/saveCart', [
		'uses' => 'ProductController@saveCart',
		'as' => 'product.savecart'
	]);
});