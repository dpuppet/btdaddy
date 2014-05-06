var handler;
$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }


    $('.btn-danger').click(function(){
        handler = $(this);
        $('#confirm_modal').modal();
    });

    $('#confirm_btn').click(function(){
        $.ajax({
            type:'POST'
            ,url:'/index.php/trade/cancel_trade'
            ,data:'id='+handler.attr('data-id')+'&type='+handler.attr('data-type')+'&xsrf_token='+ getCookie('xsrf_token')
            ,success:function(data){
                if (data.status == 'success'){
                    $('#confirm_modal').modal('toggle');
                    alert('取消成功！');
                    window.location.reload();
                }else{
                    $('#confirm_modal').modal('toggle');
                    alert(data.msg);
                }
            }
            ,dataType:'json'
        });
    });

});
