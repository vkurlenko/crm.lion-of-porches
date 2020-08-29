function deletePadding(obj){
    $(obj).parent('td').css('padding', 0);

}

function fillField(obj){
    /*var bg = $(obj).css('background-color');
    $(obj).parent('td').css('background-color', bg);*/
    //var bg = $(obj).css('background-color');
    $(obj).parent('td').addClass($(obj).attr('data-month'));
}

$(document).ready(function(){
    //deletePadding($('.m'));

    $('.m').each(function(){
        fillField($(this))
    });



})