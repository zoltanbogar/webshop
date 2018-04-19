<?php

namespace Tests\Unit;

use App\Cart;
use App\Product;
use App\Discount;
use App\Publisher;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShoppingcartTest extends TestCase
{
	private $tblTest;
	private $tblResult;
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testExample()
	{

		$this->createCases();
		$this->runTests();

		foreach($this->tblResult as $boolResult){
			if($boolResult != true){
				$this->assertTrue(false);
				break;
			}

			$this->assertTrue(true);
		}
	}

	public function createCases(){
		$this->tblTest[] = [
			"rowProductIDs" => [1001]
			, "numDiscountedPrice" => 3900
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1002]
			, "numDiscountedPrice" => 2400
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1003]
			, "numDiscountedPrice" => 3700
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1004]
			, "numDiscountedPrice" => 3700
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1005]
			, "numDiscountedPrice" => 4500
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1006]
			, "numDiscountedPrice" => 3240
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1001, 1001]
			, "numDiscountedPrice" => 7800
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1001, 1004]
			, "numDiscountedPrice" => 7800
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1004, 1004]
			, "numDiscountedPrice" => 7600
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1002, 1003, 1003]
			, "numDiscountedPrice" => 10000
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1002, 1003, 1004, 1005, 1006]
			, "numDiscountedPrice" => 17740
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1002, 1002, 1003, 1003, 1004, 1005, 1006, 1006]
			, "numDiscountedPrice" => 27080
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1002, 1002, 1003, 1003, 1003, 1004, 1005, 1006, 1006]
			, "numDiscountedPrice" => 27080
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1002, 1002, 1003, 1003, 1004, 1005, 1005, 1006, 1006]
			, "numDiscountedPrice" => 27880
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1001, 1003, 1005, 1005, 1005]
			, "numDiscountedPrice" => 17400
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1002, 1005, 1005, 1005, 1005, 1005, 1005, 1006]
			, "numDiscountedPrice" => 23640
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1002, 1005, 1005, 1005, 1006]
			, "numDiscountedPrice" => 14640
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1002, 1005, 1005, 1006]
			, "numDiscountedPrice" => 14640
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1002, 1002, 1002, 1006, 1006, 1006]
			, "numDiscountedPrice" => 16920
		];

		$this->tblTest[] = [
			"rowProductIDs" => [1001, 1001, 1002, 1006, 1006, 1006]
			, "numDiscountedPrice" => 19920
		];
	}

	public function runTests(){
		foreach($this->tblTest as $key => $rowTest){
			$objNewCart = new Cart(NULL);
			foreach($rowTest["rowProductIDs"] as $numProductID){
				$objProduct = Product::find($numProductID);
				$objNewCart->addItem($objProduct, $numProductID);
			}

			$objDiscounted = $this->ProductController_getDiscountedCart($objNewCart->objItems); //total discount

			//Total Price of the cart - total discount
			$this->tblResult[$key] = ($objNewCart->numTotalPrice - $objDiscounted["numDiscountFromTotalPrice"]) == $rowTest["numDiscountedPrice"];
		}
	}

	public function ProductController_getDiscountedCart($objCart){
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
					$numDiscountFromTotalPrice +=  $tblProduct["numPrice"] - (int)$numDiscountedPrice;
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
}
