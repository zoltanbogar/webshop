<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Shoppingcart;
use App\Product;
use App\Discount;
use App\Publisher;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Auth;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Returns with the products and their full and discounted prices if present
     */
    public function getIndex(){
		$objProducts = Product::all();

		$objProducts = $this->calculateDiscountedPrice($objProducts);

		return view('index', [
			"objProducts" => $objProducts
		]);
	}

    /**
     * @param Request $objRequest
     * @param $numProductID
     * Adds specified product to the user's cart and adds to session
     * @return mixed
     * Returns with the added product and the success HTTP code
     */
    public function getAddToCart(Request $objRequest, $numProductID){
		$objProduct = Product::find($numProductID);

		$objOldCart = Session::has('cart') ? Session::get('cart') : NULL;
		$objCart = new Cart($objOldCart);
		$objCart->addItem($objProduct, $objProduct->id);

		$objRequest->session()->put('cart', $objCart);

		return \Response::make(array('numProductID' => $numProductID), 200);
	}

    /**
     * If the user has no cart, returns it to the product view
     * Else gets the cart from session, calculates it's discounted price
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Returns with the products in the user's cart, the total price, the amount of discount and the discounted price itself
     */
    public function getCart(){
		if(!Session::has('cart')){
			return view('cart', ['tblProducts' => NULL]);
		}

		$objOldCart = Session::get('cart');
		$objCart = new Cart($objOldCart);

		$tblResult = $this->getDiscountedCart($objCart->objItems);
		$objCart = $tblResult["objCart"];

		return view('cart', [
			'tblProducts' => $objCart->objItems
			, 'numTotalPrice' => $objOldCart->numTotalPrice
			, "numDiscountFromTotalPrice" => $tblResult["numDiscountFromTotalPrice"]
			, "numDiscountedPrice" => (int)$objOldCart->numTotalPrice - (int)$tblResult["numDiscountFromTotalPrice"]
		]);
	}

    /**
     * Used when the user tries to open the cart dropdown
     * If the user has no cart saved, returns with a message and a success code
     * Else gets the cart from the session, calculates the discounts
     * @return mixed
     * Returns with the products in the user's cart, the total price, the amount of discount and the discounted price itself
     */
    public function getCartBox(){
		if(!Session::has('cart')){
			return \Response::make(array('tblProducts' => NULL, 'message' => "Cart is empty!"), 200);
		}

		$objOldCart = Session::get('cart');
		$objCart = new Cart($objOldCart);

		$tblResult = $this->getDiscountedCart($objCart->objItems);
		$objCart = $tblResult["objCart"];

		return \Response::make(array(
			'tblProducts' => $objCart->objItems
			, 'numTotalPrice' => $objOldCart->numTotalPrice
			, "numDiscountFromTotalPrice" => $tblResult["numDiscountFromTotalPrice"]
			, "numDiscountedPrice" => (int)$objOldCart->numTotalPrice - (int)$tblResult["numDiscountFromTotalPrice"]
		), 200);
	}

    /**
     * @param Request $objRequest
     * If the request is empty, forgets the cart in the session and returns with a message string and a view to redirect to
     * Else creates a new empty cart, adds all elements from the request, puts the cart in the session.
     * Calculates the discount and saves the cart into the database. Inserts if the user had no cart, updates otherwise
     * @return mixed
     */
    public function saveCart(Request $objRequest){
		if(empty($objRequest->objCart)){
			$objRequest->session()->forget('cart');

			return \Response::make(array('message' => "Cart is empty!", 'strURL' => route('product.index')), 200);
		}

		$objCart = new Cart(NULL);

		foreach($objRequest->objCart as $numProductID => $tblData){
			$objCart->numTotalQuantity += $tblData["numQuantity"];
			$objCart->numTotalPrice += $tblData["numPrice"];
		}

		$objCart->objItems = $objRequest->objCart;
		$objRequest->session()->put('cart', $objCart);

		$objSessionCart = new Cart($objRequest->session()->get('cart'));
		$rowDiscountedCart = $this->getDiscountedCart($objSessionCart->objItems);
		$numAmountOfDiscount = $rowDiscountedCart["numDiscountFromTotalPrice"];

		$objShoppingCart = Shoppingcart::firstOrNew(array('user_id' => Auth::id()));
		$objShoppingCart->user_id = Auth::id();
		$objShoppingCart->full_price = $objSessionCart->numTotalPrice;
		$objShoppingCart->discounted_price = $objSessionCart->numTotalPrice - $numAmountOfDiscount;
		$objShoppingCart->added_products = json_encode($objSessionCart);
		$objShoppingCart->save();
	}

    /**
     * @param $objCart
     * Calculates the discounts of the given cart
     * @return array
     * Returns the cart itself and the amount of discounts
     */
    public function getDiscountedCart($objCart){
		$objDiscounts = Discount::where('products_needed', 1)->get();

		$numDiscountFromTotalPrice = 0;
		
		foreach($objCart as $numProductID => &$tblProduct){
			foreach($objDiscounts as $tblDiscount){
				$objPublisher = Publisher::find($tblDiscount->publisher_id);
				if(is_array($tblProduct["objItem"])){
					if($tblProduct["objItem"]["id"] != $tblDiscount->product_id && $tblProduct["objItem"]["publisher"] != $objPublisher["name"]) continue;
				} else {
					if($tblProduct["objItem"]->id != $tblDiscount->product_id && $tblProduct["objItem"]->publisher != $objPublisher["name"]) continue;
				}
				
				if($tblDiscount->products_needed == 1){
					$numDiscountedPrice = ($tblDiscount->discount_amount != 0 ? ($tblProduct["numPrice"] - (int)$tblDiscount->discount_amount * $tblProduct["numQuantity"]) : ($tblProduct["numPrice"] - ($tblProduct["numPrice"] * (float)$tblDiscount->discount_rate)));
					$numDiscountFromTotalPrice += $tblProduct["numPrice"] - (int)$numDiscountedPrice;
					$tblProduct["numDiscountedPrice"] = (int)$numDiscountedPrice;
				}
			}
		}

		$objDiscounts = Discount::where('products_needed', '!=', 1)->get();

		$rowPrices = [];
		$numProducts = 0;

		foreach($objDiscounts as $tblDiscount){
			foreach($objCart as $numProductID => &$tblProduct){
				$objPublisher = Publisher::find($tblDiscount->publisher_id);
				if(is_array($tblProduct["objItem"])){
					if($tblProduct["objItem"]["id"] != $tblDiscount->product_id && $tblProduct["objItem"]["publisher"] != $objPublisher["name"]) continue;
				} else {
					if($tblProduct["objItem"]->id != $tblDiscount->product_id && $tblProduct["objItem"]->publisher != $objPublisher["name"]) continue;
				}

				for($i=0; $i < $tblProduct["numQuantity"]; $i++){ 
					array_push($rowPrices, $tblProduct["numPrice"] / $tblProduct["numQuantity"]);
					$numProducts++;
				}
			}

			$rowPrices = collect($rowPrices)->sort()->values();
			$numProductsGetDiscount = (int)($numProducts / $tblDiscount->products_needed * $tblDiscount->product_gets_discount);

			for($i = 0; $i < $numProductsGetDiscount; $i++){
				$numDiscountFromTotalPrice += $rowPrices[$i];
				$rowPrices->forget($i);
			}
		}

		$objNewCart = new Cart(NULL);
		foreach($objCart as $numProductID => $tblData){
			$objProduct = Product::find($numProductID);
			for($i = 0; $i < $tblData["numQuantity"]; $i++){
				$objNewCart->addItem($objProduct, $numProductID);
			}
		}

		return array("objCart" => $objNewCart, "numDiscountFromTotalPrice" => $numDiscountFromTotalPrice);
	}

    /**
     * @param $objProducts
     * Calculates the discounts of all the products in the shop
     * @return mixed
     * Returns with the products
     */
    public function calculateDiscountedPrice($objProducts){
		$tblAmountOfDiscounts = [];

		$objDiscounts = Discount::all();

		foreach($objDiscounts as $tblDiscount){
			foreach($objProducts as $key => $tblProduct){
				if(!isset($tblAmountOfDiscounts[$key]))
					$tblAmountOfDiscounts[$key] = "";

				if(($tblDiscount->publisher_id != 0 || $tblDiscount->product_id != 0) && $tblDiscount->products_needed == 1){
					if($tblDiscount->publisher_id != 0){
						$objPublisher = Publisher::find($tblDiscount->publisher_id);
						if($tblProduct->publisher != $objPublisher->name) continue;

						$numTmpDiscountedPrice = ($tblDiscount->discount_amount != 0 ? ($tblProduct->price - (int)$tblDiscount->discount_amount) : ($tblProduct->price - ($tblProduct->price * (float)$tblDiscount->discount_rate)));

						if($tblAmountOfDiscounts[$key] == false || (int)$tblAmountOfDiscounts[$key] > $numTmpDiscountedPrice)
							$tblAmountOfDiscounts[$key] = (int)$numTmpDiscountedPrice;
					} else {
						if($tblDiscount->product_id != $tblProduct->id) continue;

						$numTmpDiscountedPrice = ($tblDiscount->discount_amount != 0 ? ($tblProduct->price - (int)$tblDiscount->discount_amount) : ($tblProduct->price - ($tblProduct->price * (float)$tblDiscount->discount_rate)));

						if($tblAmountOfDiscounts[$key] == false || (int)$tblAmountOfDiscounts[$key] > $numTmpDiscountedPrice)
							$tblAmountOfDiscounts[$key] = (int)$numTmpDiscountedPrice;
					}
				}
			}
		}

		foreach($tblAmountOfDiscounts as $key => $numDiscountedPrice){
			if($numDiscountedPrice == "") continue;

			$objProducts[$key]->discounted_price = $numDiscountedPrice;
		}

		return $objProducts;
	}
}
