$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }


    $('#change_btn').click(function(){
        var old_password = $('#old_cash_password').val();
        var new_password = $('#new_password').val();
        var reconfirm = $('#reconfirm').val();
        var code = $('#code').val();
        if (new_password != reconfirm){
            $('#error-msg').html('两次新密码不一致').show();
            return;
        }
        $.ajax({
            type:'POST'
            ,url:'/safe/change_cash_password'
            ,data:'old_password='+old_password+'&new_password='+new_password+'&code='+code+'&xsrf_token='+ getCookie('xsrf_token')
            ,success:function(data){
                if (data.status == 'success'){
                    alert(data.msg);
                }else{
                    $('#error-msg').html(data.msg).show();
                    refresh_code();
                }
            }
            ,dataType:'json'
        });
    });


});

function refresh_code(){
    $('#validate_code').attr('src','/validate_code?sid='+Math.random());
}