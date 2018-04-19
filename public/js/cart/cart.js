function deleteProductFromCart(numProductID){
    var objProduct = $('li.list-group-item[product_id="' + numProductID + '"]');
    var numQuantity = objProduct.children().find('input').val();

    Adjust.adjustCartTotalPrice(-numQuantity, objProduct.children().find('input'));
    Adjust.adjustCartTotalQuantity(-numQuantity);
    objProduct.remove();
    delete Adjust.objTblProducts[numProductID];

    if($.isEmptyObject(Adjust.objTblProducts)){
        $('.product_container').html(
            '<div class="col-sm-6 col-md-6 cold-md-offset-3 col-sm-offset-3">' +
            '	<h2>No items in cart!</h2>' +
            '</div>'
        );
        $('.cart_price_container').remove();
    }
}