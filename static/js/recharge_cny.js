var handler;
$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }



    $('#recharge_btn').click(function(){
        var amount = $('#amount').val();
        $.ajax({
            type:'POST'
            ,url:'/recharge/cny_method/gate_way'
            ,data:'amount='+amount+'&xsrf_token='+ getCookie('xsrf_token')
            ,success:function(data){
                if (data.status == 'success'){
                    $('#confirm_text').html('充值金额:'+amount+'<br>支付方式：网银支付<br><br><div class="alert alert-error">因大部分网银只支持IE内核，建议您使用IE浏览器或IE内核浏览器进行充值</div>');
                    $('#pay_form').html(data.msg);
                    $('#confirm_modal').modal();
                }else{
                    alert(data.msg);
                }
            }
            ,dataType:'json'
        });
    });



    $('#amount').keyup(function(){
        var price = num_need_fix($(this).val(),2);
        if (price){
            $(this).val(price);
        }
    });

    $('input').keydown(function(e){
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