<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * @package App
 */
class Cart{
    /**
     * @var null
     */
    public $objItems = NULL;
    /**
     * @var int
     */
    public $numTotalQuantity = 0;
    /**
     * @var int
     */
    public $numTotalPrice = 0;

    /**
     * Cart constructor.
     * @param $objOldItems
     */
    public function __construct($objOldItems){
		if($objOldItems){
			$this->objItems = $objOldItems->objItems;
			$this->numTotalQuantity = $objOldItems->numTotalQuantity;
			$this->numTotalPrice = $objOldItems->numTotalPrice;
		}
	}

    /**
     * @param $objItem
     * Adds an item to the user's cart and calculates the total price and the total quantity
     * @param $numProductID
     */
	public function addItem($objItem, $numProductID){
		$tblStoredItem = [
			'numQuantity' => 0
			, 'numPrice' => $objItem->price
			, 'objItem' => $objItem
		];

		if($this->objItems){
			if(array_key_exists($numProductID, $this->objItems)){
				$tblStoredItem = $this->objItems[$numProductID];
			}
		}

		$tblStoredItem["numQuantity"]++;
		$tblStoredItem["numPrice"] = $objItem->price * $tblStoredItem["numQuantity"];

		$this->objItems[$numProductID] = $tblStoredItem;

		$this->numTotalQuantity++;
		$this->numTotalPrice += $objItem->price;
	}
}