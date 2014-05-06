$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }

    $('#signup_button').click(function(){
        var email = $('#email').val();
        var password = $('#password').val();
        var code = $('#code').val();
        $.ajax({
            type:'POST'
            ,url:'signup/do_signup'
            ,data:'email='+email+'&password='+password+'&xsrf_token='+ getCookie('xsrf_token')+'&validate_code='+ code
            ,success:function(data){
                if (data.status == 'success'){
                    alert('注册成功');
                    location.href="/";
                }else{
                    $('#msg').html(data.msg).show();
                    refresh_code();
                }
            }
            ,dataType:'json'
        });
        return false;
    });


});

function refresh_code(){
    $('#validate_code').attr('src','/validate_code?sid='+Math.random());
}