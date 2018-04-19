$('li.cart_dropdown_menu_item').on('click', function(objEvent){
    if($('li.cart_dropdown_menu_item').hasClass('show')){
        $('li.cart_dropdown_menu_item').removeClass('show');
        $('li.cart_dropdown_menu_item').children('div').removeClass('show');
    } else {
        getCartBoxData();
    }
});

function getCartBoxData(){
    $.ajax({
        type: "GET"
        , url: "/cart-box"
        , data: {
            _token: '{{csrf_token()}}'
        }
        , dataType: "json"
        , success: function (tblResult) {
            addDataToCartBoxList(tblResult);
        }
        , error: function(objXHR, tblResult) {
            console.log(objXHR, tblResult);
        }
    });
}