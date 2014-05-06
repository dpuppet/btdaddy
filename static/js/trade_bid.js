$(function(){
    function getCookie(name){
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    }


    $('#best_bid_price').click(function(){
        $('#bid_price').val($(this).html());
    });

    $('#balance').click(function(){
        var bid_price = $('#bid_price').val();
        var balance = $(this).html();
        $('#bid_cost').val(balance);
        $('#bid_amount').val(num_fix(balance/bid_price,4));
    });

    $('#bid_btn').click(function(){
        var bid_price = $('#bid_price').val();
        var bid_amount = $('#bid_amount').val();
        var bid_cost = $('#bid_cost').val();
        $('#confirm_text').html('价格：￥'+bid_price +'数量：'+bid_amount+'<br>总费用：'+bid_cost);
        $('#confirm_modal').modal();
        return false;
    });


    $('#confirm_btn').click(function(){

        var bid_price = $('#bid_price').val();
        var bid_amount = $('#bid_amount').val();
        var bid_cost = $('#bid_cost').val();
        var balance = $('#balance').html();
        if (balance - bid_cost <0){
            $('#error-msg').html('余额不足！').show();
            return true;
        }
        if (bid_price == '' || bid_amount == '' || bid_cost == ''){
            $('#error-msg').html('请填写完整！').show();
            return;
        }
        $(this).html('订单提交中...').attr('disabled',true);
        $.ajax({
            type:'POST'
            ,url:'/trade/do_bid'
            ,data:'type=btc&amount='+bid_amount+'&price='+bid_price+'&xsrf_token='+ getCookie('xsrf_token')
            ,success:function(data){
                if (data.status == 'success'){
                    $('#success-msg').html('下单成功！').show();
                    $('#error-msg').hide();
                    $('#bid_amount').val(0);
                    $('#confirm_modal').modal('toggle');
                    $('#confirm_btn').html('确认').removeAttr('disabled');
                }else{
                    $('#error-msg').html(data.msg).show();
                    $('#success-msg').hide();
                    $('#confirm_modal').modal('toggle');
                    $('#confirm_btn').html('确认').removeAttr('disabled');
                }
            }
            ,dataType:'json'
        });
    })
    $('input').keydown(function(e){
        //alert(check_number(e));

        return check_number(e);
    });


    $('#bid_amount').keyup(function(){
        var amount = num_need_fix($(this).val(),4);
        if (amount){
            $(this).val(amount);
        }
        var cost = $(this).val() * $('#bid_price').val();
        cost = num_fix(cost,2);
        $('#bid_cost').val(cost);
    })

    $('#bid_price').keyup(function(){
        var price = num_need_fix($(this).val(),2);
        if (price){
            $(this).val(price);
        }
        var cost = $(this).val() * $('#bid_amount').val();
        cost = num_fix(cost,2);
        $('#bid_cost').val(cost);
    });

    $('#bid_cost').keyup(function(){
        var cost = num_need_fix($(this).val(),2);
        if (cost){
            $(this).val(cost);
        }

        var amount = $(this).val() / $('#bid_price').val();
        amount = num_fix(amount,4);
        $('#bid_amount').val(amount);
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
function num_fix(b, c) {
    b = Number(b).toFixed(15);
    var a = num_need_fix(b, c);
    return a ? a : b
}

function refresh_realtime_price(){
    $.getJSON('/api/recent_deals/real_time_price',function(e){
        $('#best_bid_price').html(e.bid1);
        $('#newest_deal_price').html(e.newest_deal_price);
        $('#bid1').html(e.bid1);
        $('#ask1').html(e.ask1);
        $('#highest_price').html(e.highest_price);
        $('#lowest_price').html(e.lowest_price);
        $('#today_amount').html(e.today_amount);
    })
}

setInterval("refresh_realtime_price()",10000);