<?php


/*
 * 输出json提示信息辅助函数
 */
function show_msg($type,$msg){
    $status = $type==0?'error':'success';
    $arr=array(
               'status'=>$status,
               'msg'=>$msg
              );
    echo (json_encode($arr));
}