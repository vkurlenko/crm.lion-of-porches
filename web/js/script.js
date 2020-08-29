function deletePadding(obj){
    $(obj).parent('td').css('padding', 0);
}

// раскраска дат рождения
function fillField(obj){
    $(obj).parent('td').addClass($(obj).attr('data-month'));
}

$(document).ready(function(){
    //deletePadding($('.m'));

    // раскраска дат
    /*$('.m').each(function(){
        fillField($(this))
    });*/


    $('#search-reset').click(function() {
        window.location = "/";
    })


})