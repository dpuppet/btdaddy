<?php
/*
 * 校验数字是否合法，必须是一个保留$length位小数点的正数
 * 0或负数都是非法
 */
function is_valid_number($data,$length){
    //var_dump(number_format($data,$length));
    if (is_numeric($data) && $data > 0){
        return round($data,$length);
    }
    return false;
}


/*
 * 校验是否为合法货币类型
 * 现在只支持 BTC和LTC 两种类型，此处注意扩展性
 */
function is_valid_cash_type($type){
    $type_array = array('btc','ltc');
    if(in_array(strtolower($type),$type_array)){
        return strtolower($type);
    }else{
        return false;
    }
}


/*
 * 补全 $length 位 0
 */
function number_fix($data,$length){
    if (strpos($data,".") == false){
        return $data.'.00';
    }else{
        $tmp = substr($data,strpos($data,"."));
        if (strlen($tmp) == 1){
            return $data.'0';
        }else{
            return $data;
        }
    }
}