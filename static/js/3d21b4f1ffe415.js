$(function(){
    $('.btn-navbar').click(function(){
        if ($(this).hasClass('collapsed')){
            $(this).removeClass('collapsed')
            $('.nav-collapse').css('height','auto');
        }else{
            $(this).addClass('collapsed');
            $('.nav-collapse').css('height','0px');
        }
    });
})