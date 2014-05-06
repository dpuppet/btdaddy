<?php
/*
 * @property Log $log
 */
class Log extends CI_Model {
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Shanghai');
    }

    /*
     * 记录撮合脚本运行日志
     */
    public  function log_trade_match($cny,$btc,$ltc,$start_time){
        $this->db->set(array('cny'=>$cny,
                             'btc'=>$btc,
                             'ltc'=>$ltc,
                             'start_time'=>$start_time,
                             'end_time'=>date("Y-m-d H:i:s")
                            )
        );
        $this->db->insert('l_transaction_script_log');
    }

    /*
     * 记录用户钱包操作记录
     * type=>钱币类型
     * op_type=>收入还是支出
     */
    public function log_wallet($uid,$type,$op_type,$num,$comment){
        $this->db->set(array('user_id'=>$uid,
                             'type'=>$type,
                             'op_type'=>$op_type,
                             'num'=>$num,
                             'comment'=>$comment
                            )
                     );
        $this->db->insert('wallet_log');
    }
}