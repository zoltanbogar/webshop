function addToCart(numProductID){
    $.ajax({
        type: "GET"
        , url: "/add-to-cart/"+numProductID
        , data: {
            _token: '{{csrf_token()}}'
        }
        , dataType: "json"
        , success: function (tblResult) {
            incrementCartTotalQuantity();
        }
        , error: function(objXHR, tblResult) {
            console.log(objXHR, tblResult);
        }
    });
}

function incrementCartTotalQuantity(){
    var strTotalQuantity = $('.cart_badge').text();
    var numTotalQuantity = parseInt(strTotalQuantity);
    if(isNaN(numTotalQuantity)){
        numTotalQuantity = 0;
    }
    numTotalQuantity++;
    $('.cart_badge').text(numTotalQuantity);
}