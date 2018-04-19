var Adjust = function(objTblProducts){
    this.adjustCart;
    this.adjustCartObject;
    this.adjustCartTotalPrice;
    this.adjustCartTotalQuantity;

    this.objTblProducts = objTblProducts;
}

$(document).ready(function(){
    $('.input_spinner').on('input', function(objEvent){
        Adjust.adjustCart(objEvent.target.value - $(objEvent.target).attr('latest_value'), objEvent);
        $(objEvent.target).attr('latest_value', objEvent.target.value);
    });
});

Adjust.prototype.adjustCart = function(numChange, objEvent){
    this.adjustCartTotalQuantity(numChange);
    this.adjustCartTotalPrice(numChange, objEvent.target);
}

Adjust.prototype.adjustCartTotalQuantity = function(numChange){
    var strTotalQuantity = $('.cart_badge').text();
    var numTotalQuantity = parseInt(strTotalQuantity);

    numTotalQuantity += parseInt(numChange);
    $('.cart_badge').text(numTotalQuantity);
}

Adjust.prototype.adjustCartTotalPrice = function(numChange, objEventTarget){
    var numTotalPrice = parseInt($('.cart_total_price').text());
    var objInput = $(objEventTarget);
    var numProductID =  objInput.closest('li').attr('product_id');

    numTotalPrice += parseInt(this.objTblProducts[numProductID]["objItem"]["price"]) * numChange;
    $('.cart_total_price').text(numTotalPrice);

    this.adjustCartObject(numProductID, numChange);
}

Adjust.prototype.adjustCartObject = function(numProductID, numChange){
    this.objTblProducts[numProductID]["numQuantity"] = parseInt(this.objTblProducts[numProductID]["numQuantity"]) + parseInt(numChange);
    this.objTblProducts[numProductID]["numPrice"] = parseInt(this.objTblProducts[numProductID]["numPrice"]) + parseInt(this.objTblProducts[numProductID]["objItem"]["price"]) * parseInt(numChange);
}

var Adjust = new Adjust(objTblProducts);