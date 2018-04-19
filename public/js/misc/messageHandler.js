function handleError(response){
    var tblError;

    if(typeof response.responseJSON != 'undefined' && !$.isEmptyObject(response.responseJSON.errors)){
        tblError = response.responseJSON.errors;
    } else if(typeof response.responseJSON != 'undefined' && !$.isEmptyObject(response.responseJSON.msg)){
        tblError = response.responseJSON.msg;
    } else if(typeof response.responseJSON != 'undefined' && !$.isEmptyObject(response.responseJSON.message)){
        tblError = response.responseJSON.message;
    } else if(typeof response.msg != 'undefined' && !$.isEmptyObject(response.msg)){
        tblError = response.msg;
    } else {
        tblError = response.message;
    }

    addMessageToContainer(tblError, 'danger');

    removeMessageAfterAWhile();
}

function handleSuccess(response){
    var tblSuccess;

    if(typeof response.responseJSON != 'undefined' && !$.isEmptyObject(response.responseJSON.msg)){
        tblSuccess = response.responseJSON.msg;
    } else if(typeof response.responseJSON != 'undefined' && !$.isEmptyObject(response.responseJSON.message)){
        tblSuccess = response.responseJSON.message;
    } else if(typeof response.msg != 'undefined' && !$.isEmptyObject(response.msg)){
        tblSuccess = response.msg;
    } else {
        tblSuccess = response.message;
    }

    addMessageToContainer(tblSuccess, 'success');

    removeMessageAfterAWhile();
}

function addMessageToContainer(tblMessage, className){
    var content = ''+
        '				<div class="row">'+
        '					<div class="col-md-12">'+
        '						<blockquote class="blockquote bq-'+className+' signup_block signup_message_'+className+'">'+
        '							<ul>';
    if(typeof tblMessage == 'string'){
        content += '				<li>' + tblMessage + '</li>';
    } else {
        $.each(tblMessage, function(i,e){
            content += '			<li>' + e + '</li>';
        });
    }
    content += '				</ul>'+
        '						</blockquote>'+
        '					</div>'+
        '				</div>';
    $('.messageContainer').html(content);
}

function removeMessageAfterAWhile(){
    var objRow = $('.messageContainer').find('.row');
    numID = window.setTimeout(function(){
        objRow.fadeOut('slow').promise().done(function(){
            objRow.remove();
        });
    }, 3000);
    clearTimeout(numID-1);
}