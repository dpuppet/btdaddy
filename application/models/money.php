<?php
class Money extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('log');
    }

    /*
     * 增加用户钱包对应币种数量
     * 同时记录钱包操作日志
     */
    public function add_wallet($uid,$type,$num,$comment){
        $this->db->where('user_id',$uid);
        $this->db->set($type,$type.'+'.$num,false);
        $this->db->update('user_wallet');
        $this->log->log_wallet($uid,$type,'income',$num,$comment);
        return true;
    }

    /*
     * 减去用户钱包对应币种数量
     * 同时记录钱包操作日志
     */
    public function deduct_wallet($uid,$type,$num,$comment){
        $this->db->where('user_id',$uid);
        $res = $this->db->get('user_wallet');
        if ($res->num_rows() >0){
            if ($res->row()->$type < $num){
                return false;
            }
        }else{
            return false;
        }
        $this->db->where('user_id',$uid);
        $this->db->set($type,$type.'-'.$num,false);
        $this->db->update('user_wallet');
        $this->log->log_wallet($uid,$type,'outcome',$num,$comment);
        return true;
    }
}