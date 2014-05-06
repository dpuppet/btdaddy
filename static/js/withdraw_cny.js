var handler;
$(function(){



    $('#withdraw_btn').click(function(){
        var amount = $('#amount').val();
        var method = $('#method').val();
        var card_no = $('#card_no').val();
        var card_name = $('#card_name').val();
        var card_addr = $('#card_addr').val();
        var cash_password = $('#cash_password').val();
        var code = $('#code').val();
        var fee = amount*0.005 + 5;
        $('#confirm_text').html('提现金额:'+amount+'<br>提现方式：'+method+'<br>提现账号：'+card_no+'<br>开户人：'+card_name+'<br>开户行信息：'+card_addr+'<br>手续费：'+fee+'元');
        $('#confirm_modal').modal();
    });

    $('#confirm_btn').click(function(){
        $('#confirm_modal').modal('toggle');

        do_withdraw();
    });

    $('#amount').keyup(function(){
        var price = num_need_fix($(this).val(),2);
        if (price){
            $(this).val(price);
        }
    });

    $('.number').keydown(function(e){
        return check_number(e);
    });
});

function check_number(b) {
    var a = b.keyCode;
    if (b.ctrlKey || b.altKey) {
        return true
    }
    if (!((a >= 48 && a <= 57) || (a >= 96 && a <= 105) || (a == 8) || (a == 46) || (a == 27) || (a == 37) || (a == 39) || (a == 16) || (a == 9) || (a == 33) || (a == 34) || (a == 190) || (a >= 91 && a <= 93) || (a >= 110 && a < 124))) {
        return false
    }
    return true
}

function num_need_fix(b, c) {
    b = b.toString();
    var a = b.indexOf(".");
    if (a < 0) {
        return false
    }
    if (b.length - a - 1 > c) {
        if (c > 0) {
            return b.substr(0, a + c + 1)
        } else {
            return b.substr(0, a)
        }
    }
    return false
}

function refresh_code(){
    $('#validate_code').attr('src','/validate_code?sid='+Math.random());
}

function getCookie(name){
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

function do_withdraw(){
    var amount = $('#amount').val();
    var method = $('#method').val();
    var card_no = $('#card_no').val();
    var card_name = $('#card_name').val();
    var card_addr = $('#card_addr').val();
    var cash_password = $('#cash_password').val();
    var code = $('#code').val();
    $.ajax({
        type:'POST'
        ,url:'/withdraw/cny_method/do_withdraw'
        ,data:'amount='+amount+'&method='+method+'&card_no='+card_no+'&card_name='+card_name+'&card_addr='+card_addr+'&cash_password='+cash_password+'&code='+code+'&xsrf_token='+ getCookie('xsrf_token')
        ,success:function(data){
            if (data.status == 'success'){
                alert(data.msg);
                refresh_code();
            }else{
                alert(data.msg);
                refresh_code();
            }
        }
        ,dataType:'json'
    });
}