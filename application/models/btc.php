<?php
class Btc extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('money');
    }

    /*
     * 检验比特币充值hash的状态
     * return value {'used','confirming','new'}
     */
    public function get_hash_status($hash){
        $this->db->where('transaction_hash',$hash);
        $res = $this->db->get('btc_recharge_record');
        if ($res->num_rows()>0){
            if ($res->row()->confirmations >=4){
                return 'used';
            }else{
                return 'confirming';
            }
        }else{
            return 'new';
        }
    }

    /*
     * 创建比特币交易记录
     */
    public function new_recharge($data){
        $this->db->set(array('user_id'=>$data['uid'],
                             'input_address'=>$data['input_address'],
                             'input_transaction_hash'=>$data['input_transaction_hash'],
                             'transaction_hash'=>$data['transaction_hash'],
                             'value'=>$data['value']/100000000,
                             'confirmations'=>0,
                             'time'=>date('Y-m-d H:i:s',time())
                            )
                      );
        $this->db->insert('btc_recharge_record');
    }

    /*
     * 更新交易确认次数
     */
    public function update_confirmations($hash,$confirmations){
        $this->db->where('transaction_hash',$hash);
        $this->db->set('confirmations',$confirmations);
        $this->db->update('btc_recharge_record');
    }


    /*
     *创建用户BTC充值地址
     */
    public function create_btc_receive_address($uid){

        $my_address = $this->config->item('my_btc_address');
        $secret = $this->_gen_secret_key(30);
        $my_callback_url = $this->config->item('btc_callback_url').'?uid='.$uid.'&secret='.$secret;
        $root_url = 'https://blockchain.info/api/receive';
        $parameters = 'method=create&address=' . $my_address . '&callback=' . urlencode($my_callback_url);
        $response = file_get_contents($root_url . '?' . $parameters);
        $object = json_decode($response);

        if ($object){
            if ($object->destination != $my_address){
                return false;
            }
            $this->db->set(array('btc_address'=>$object->input_address,
                                 'secret_key' =>$secret
                )
            );
            $this->db->where('user_id',$uid);
            $this->db->update('user_bind_address');
            return true;
        }else{
            return false;
        }
    }



    /*
     * 获取用户BTC充值地址callback私钥
     */
    public function get_btc_info($uid){
        $this->db->where('user_id',$uid);
        $result = $this->db->get('user_bind_address');
        if ($result->num_rows()>0){
            return $result->row();
        }else{
            return false;
        }
    }


    /*
     * 生成用户私钥
     */
    private function _gen_secret_key($n){
        $str = "0123456789abcdefghijklmnopqrstuvwxyz";
        $s ='';
        $len = strlen($str)-1;
        for($i=0 ; $i<$n; $i++){
            $s .=$str[rand(0,$len)];
        }
        return $s;
    }


    /*
     * 获取用户BTC充值地址
     */
    public function get_btc_address(){
        $this->db->where('user_id',$_SESSION['uid']);
        $data = $this->db->get('user_bind_address');
        if ($data->row()->btc_address == ''){
            if ($this->create_btc_receive_address($_SESSION['uid'])){
                $this->db->where('user_id',$_SESSION['uid']);
                $data = $this->db->get('user_bind_address');
                $btc_address = $data->row()->btc_address;
            }else{
                return false;
            }
        }else{
                $btc_address = $data->row()->btc_address;
        }
        return $btc_address;
    }

    /*
     * 获取用户充值记录
     */
    public  function get_recharge_list(){
        $this->db->where('user_id',$_SESSION['uid']);
        $this->db->order_by('time','DESC');
        $data = $this->db->get('btc_recharge_record');
        if ($data->num_rows()>0){
            return $data->result_array();
        }else{
            return null;
        }
    }

    /*
     * 获取用户充值记录的HTML格式
     */
    public function get_recharge_list_html(){
        $raw_data = $this->get_recharge_list();
        if ($raw_data == null){
            return null;
        }else{
            $html = '';
            foreach($raw_data as $row){
                $value = round($row['value'],4);
                switch($row['confirmations']){
                    case 0:
                        $status = '<a target="_blank" href ="https://blockchain.info/zh-cn/tx/'.$row["input_transaction_hash"].'"><button class="btn btn-small btn-danger">未确认</button></a>';
                        break;
                    default:
                        $status = '<a target="_blank" href ="https://blockchain.info/zh-cn/tx/'.$row["input_transaction_hash"].'"><button class="btn btn-small btn-success">'.$row['confirmations'].'次确认</button></a>';
                        break;
                }
                $html .=<<<HTML
                            <tr><td>$row[id]</td>
                                <td class="center">$row[time]</td>
                                <td class="center">$value</td>
                                <td class="center">$status</td>
                               </tr>
HTML;
            }

        }
        return $html;
    }



    /**************************
     ***** 比特币提取模块 *******
     **************************/

    public function get_withdraw_list(){
        $this->db->where(array('user_id'=>$_SESSION['uid']));
        $this->db->order_by('time','DESC');
        $data = $this->db->get('btc_withdraw_record');
        if ($data->num_rows()>0){
            return $data->result_array();
        }else{
            return null;
        }
    }


    public function get_withdraw_list_html(){
        $raw_data = $this->get_withdraw_list();
        if ($raw_data != null){
            $html = '';
            foreach($raw_data as $row){
                switch($row['status']){
                    case 0:
                        $status = '<span class="label label-warning">等待处理</span>';
                        break;
                    case 1:
                        $status = '<span class="label label-success">提取完成</span>';
                        break;
                    case 2:
                        $status = '<span class="label label-danger">请求驳回</span>';
                        break;
                }
                $amount = round($row['value'],4);
                $fee = round($row['fee'],4);
                $time = $row['time'];
                $html .=<<<HTML
                            <tr><td>$row[id]</td>
                                <td class="center">$row[btc_address]</td>
                                <td class="center">$amount</td>
                                <td class="center">$fee</td>
                                <td class="center">$status</td>
                                <td class="center">$row[remark]</td>
                                <td class="center">$time</td>
                            </tr>
HTML;
            }
            return $html;
        }else{
            return null;
        }
    }


    /*
     * 创建提取比特币申请单
     */

    public function create_withdraw($amount,$btc_address){
        $fee = 0.0005;
        $this->load->model('user');
        $user_cash = $this->user->wallet()->btc;
        if ($amount+$fee > $user_cash){
            return false;
        }
        $this->db->trans_start();
        $this->db->set(array('user_id'=>$_SESSION['uid'],
                'value'=>$amount,
                'btc_address'=>$btc_address,
                'fee'=>$fee)
        );
        $this->db->insert('btc_withdraw_record');
        $this->money->deduct_wallet($_SESSION['uid'],'btc',$fee+$amount,'提取锁定');
        $this->db->trans_complete();
        $trans_ok = $this->db->trans_status();
        if ($trans_ok === FALSE){
            return false;
        }else{
            return true;
        }
    }

}