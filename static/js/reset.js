$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }

    $('#reset_button').click(function(){
        var email = $('#email').val();
        var password = $('#password').val();
        var code = $('#code').val();
        $.ajax({
            type:'POST'
            ,url:'reset/do_reset'
            ,data:'email='+email+'&xsrf_token='+ getCookie('xsrf_token')+'&validate_code='+ code
            ,success:function(data){
                if (data.status == 'success'){
                    $('#success-msg').html(data.msg).show();
                    $('#msg').hide();
                    refresh_code();
                }else{
                    $('#msg').html(data.msg).show();
                    $('#success-msg').hide();
                    refresh_code();
                }
            }
            ,dataType:'json'
        });
        return false;
    });

    $('#reset_password_button').click(function(){
        var new_password = $('#new_password').val();
        var confirm_new_password = $('#confirm_new_password').val();
        var password_token = $('#password_token').val();
        $.ajax({
            type:'POST'
            ,url:'/reset/do_password'
            ,data:'new_password='+new_password+'&confirm_new_password='+ confirm_new_password+'&password_token='+ password_token+'&xsrf_token='+ getCookie('xsrf_token')
            ,success:function(data){
                if (data.status == 'success'){
                    location.href="/";
                }
                else
                {
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
    $('#validate_code').attr('src','validate_code?sid='+Math.random());
}