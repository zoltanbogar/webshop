<?php

namespace App\Http\Controllers;

use App\User;
use App\Cart;
use App\Shoppingcart;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSignup(){
		return view('signup');
	}

    /**
     * @param Request $objRequest
     * Validates the signup request, saves the data and logs the user in
     * @return mixed
     * Message string and view to redirect to
     */
    public function postSignup(Request $objRequest){
		$this->validate($objRequest, [
			'email' => 'email|required|unique:users',
			'password' => 'required|min:6',
			'name' => 'required|max:50'
		]);

		$objUser = new User([
			'email' => $objRequest->input('email'),
			'password' => bcrypt($objRequest->input('password')),
			'name' => $objRequest->input('name')
		]);

		$objUser->save();

		Auth::login($objUser);

		return \Response::make(array('message' => "The user with email: " . $objRequest->input('email') . " has been created!", 'strURL' => route('product.index')), 201);
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSignin(){
		return view('signin');
	}

    /**
     * @param Request $objRequest
     * Validates the login request, tried to log the user in and fill its saved cart
     * @return mixed
     * Message string, view if success else none
     */
    public function postSignin(Request $objRequest){
		$this->validate($objRequest, [
			'email' => 'email|required',
			'password' => 'required|min:6'
		]);


		if(Auth::attempt(['email' => $objRequest->input('email'), 'password' => $objRequest->input('password')])){
			$objShoppingCart = Shoppingcart::where('user_id', Auth::id())->first();
			if($objShoppingCart != NULL)
				$this->addUsersCartToSession($objShoppingCart);

			return \Response::make(array('message' => "Successfully logged in!", 'strURL' => route('product.index')), 200);
		}

		return \Response::make(array('message' => "Failed to log in!"), 403);
	}

    /**
     * Logs the user out and deletes the cart session and redirects the user back
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout(){
		Auth::logout();

		Session::forget('cart');

		return redirect()->back();
	}

    /**
     * @param $objShoppingCart
     * Creates the cart and add to session
     */
    public function addUsersCartToSession($objShoppingCart){
		$objCart = json_decode($objShoppingCart->added_products, true);

		$objNewCart = new Cart(NULL);
		foreach($objCart["objItems"] as $numProductID => $tblData){
			$objNewCart->numTotalQuantity += $tblData["numQuantity"];
			$objNewCart->numTotalPrice += $tblData["numPrice"];
		}

		$objNewCart->objItems = $objCart["objItems"];

		Session::put('cart', $objNewCart);
	}
}
