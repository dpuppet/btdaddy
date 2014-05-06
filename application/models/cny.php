<?php
class Cny extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('money');
    }

    /*
     * 生成支付URL
     */
    public function get_recharge_url($bill_no,$amount){
        $mer_no = $this->config->item('merchant_id','payment');
        $order_time = date('YmdHis',time());
        $return_url = $this->config->item('return_url','payment');
        $advice_url = $this->config->item('advice_url','payment');
        $sign_info = $this->get_sign_info($mer_no,$bill_no,$amount,$return_url);
        $product = $this->config->item('product','payment');
        $remark = $_SESSION['email'].'_'.$amount.'_'.$order_time;
        /*
        $url = $this->config->item('payment_url','payment');
        $url .='?Merno='.$mer_no;
        $url .='&BillNo='.$bill_no;
        $url .='&Amount='.$amount;
        $url .='&ReturnURL='.$return_url;
        $url .='&AdviceURL='.$advice_url;
        $url .='&SignInfo='.$sign_info;
        $url .='&orderTime='.$order_time;
        */
        $url = "<input type='hidden' name='MerNo' value=$mer_no>";
        $url .= "<input type='hidden' name='BillNo' value=$bill_no>";
        $url .= "<input type='hidden' name='Amount' value=$amount>";
        $url .= "<input type='hidden' name='ReturnURL' value=$return_url>";
        $url .= "<input type='hidden' name='AdviceURL' value=$advice_url>";
        $url .= "<input type='hidden' name='SignInfo' value=$sign_info>";
        $url .= "<input type='hidden' name='orderTime' value=$order_time>";
        $url .= "<input type='hidden' name='products' value=$product>";
        $url .= "<input type='hidden' name='Remark' value=$remark>";
        $url .= '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>';
        $url .= '<button type="submit" class="btn btn-primary" id="confirm_btn">确认支付</button>';

        return $url;
    }


    /*
     * 生成交易签名
     */
    public function get_sign_info($mer_no,$bill_no,$amount,$return_url){
        $md5_key = $this->config->item('md5_key','payment');
        $str = $mer_no.'&'.$bill_no.'&'.$amount.'&'.$return_url.'&'.$md5_key;
        return strtoupper(md5($str));
    }

    /*
     * 生成交易流水单
     */
    public function create_bill($amount){
        $this->db->set(array('user_id'=>$_SESSION['uid'],
                             'value'=>$amount,
                             'status'=>0,
                             'order_time'=>date('Y-m-d H:i:s',time())
                            )
        );
        $this->db->insert('cny_recharge_record');
        return $this->db->insert_id();
    }

    /*
     * 获取用户交易成功记录
     */
    public function get_recharge_list(){
        $this->db->where(array('status'=>1,
                               'user_id'=>$_SESSION['uid']
                              )
        );
        $data = $this->db->get('cny_recharge_record');
        if ($data->num_rows()>0){
            return $data->result_array();
        }else{
            return null;
        }
    }

    /*
     * 返回用户交易成功记录HTML
     */
    public function get_recharge_list_html(){
        $raw_data = $this->get_recharge_list();
        if ($raw_data == null){
            return null;
        }else{
            $html = '';
            foreach($raw_data as $row){
                $html .=<<<HTML
                            <tr><td>$row[id]</td>
                                <td class="center">$row[order_time]</td>
                                <td class="center">$row[value]</td>
                                <td class="center"><span class="label label-success">成功</span></td>
                                <td class="center">
                                    $row[remark]
                                </td></tr>
HTML;
            }

        }
        return $html;
    }

    /*
     * 获取充值流水号状态,同时检测$amount是否与数据库存储相同
     */
    public function get_bill_status($bill_no,$amount){
        $this->db->where('id',$bill_no);
        $data = $this->db->get('cny_recharge_record');
        if ($data->num_rows()>0){
            if ($data->row()->status != 0){
                return false;
            }else{
                if ($amount != round($data->row()->value,2)){
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            return false;
        }
    }

    /*
     * 更新充值流水单状态
     */
    public function update_bill($bill_no,$success,$result){
        $this->db->where('id',$bill_no);
        $this->db->set(array('status'=>$success,
                             'remark'=>$result
                            )
        );
        $this->db->update('cny_recharge_record');
    }

    /*
     * 确认充值交易，充值到用户钱包
     */
    /**
     * @param $bill_no
     * @param $amount
     * @param $remark
     */
    public function confirm_bill($bill_no,$amount,$remark){
        //$this->load->model('money');
        $this->db->where('id',$bill_no);
        $result = $this->db->get('cny_recharge_record');
        $user_id = $result->row()->user_id;

        $this->db->trans_start();
        $this->db->set(array('status'=>1,
                             'remark'=>$remark
                            )
        );
        $this->db->where('id',$bill_no);
        $this->db->update('cny_recharge_record');
        $this->money->add_wallet($user_id,'cny',$amount,'网银在线充值：'.$bill_no);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }


    /**************************
     ***** 人民币提现模块 *******
     **************************/

    public function get_withdraw_list(){
        $this->db->where(array('user_id'=>$_SESSION['uid']));
        $this->db->order_by('time','DESC');
        $data = $this->db->get('cny_withdraw_record');
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
                        $status = '<span class="label label-success">提现完成</span>';
                        break;
                    case 2:
                        $status = '<span class="label label-danger">请求驳回</span>';
                        break;
                }
                $amount = round($row['value'],2);
                $fee = round($row['fee'],2);
                $time = $row['time'];
                $html .=<<<HTML
                            <tr><td>$row[id]</td>
                                <td class="center">$row[card_no]</td>
                                <td class="center">$row[method]</td>
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
     * 创建提现申请单
     */
    /**
     * @param $amount
     * @param $method
     * @param $card_no
     * @param $card_addr
     * @param $card_name
     * @return bool
     */
    public function create_withdraw($amount,$method,$card_no,$card_addr,$card_name){
        $fee = $amount*0.05 + 5;
        $this->load->model('user');
        $user_cash = $this->user->wallet()->cny;
        if ($amount+$fee > $user_cash){
            return false;
        }
        $this->db->trans_start();
        $this->db->set(array('user_id'=>$_SESSION['uid'],
                             'value'=>$amount,
                             'method'=>$method,
                             'card_no'=>$card_no,
                             'card_addr'=>$card_addr,
                             'card_name'=>$card_name,
                             'fee'=>$fee)
        );
        $this->db->insert('cny_withdraw_record');
        $this->money->deduct_wallet($_SESSION['uid'],'cny',$fee+$amount,'提现锁定');
        $this->db->trans_complete();
        $trans_ok = $this->db->trans_status();
        if ($trans_ok === FALSE){
            return false;
        }else{
            return true;
        }
    }


}