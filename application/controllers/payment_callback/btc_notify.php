<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class btc_notify extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('btc','money','user'));
    }

    public function index()
    {
        show_404();
    }

    /*
     * 比特币充值回调确认
     */
    public  function receive_btc_confirmation(){

        //校验请求参数完整度

        if ( empty($_GET['value']) || empty($_GET['input_address']) || !isset($_GET['confirmations']) || empty($_GET['transaction_hash']) || empty($_GET['input_transaction_hash']) || empty($_GET['destination_address'])){
            exit('parameters not complete');
        }
        $this->load->model('btc');
        $user_btc = $this->btc->get_btc_info($this->input->get('uid'));
        if ($user_btc){
            $real_secret = $user_btc->secret_key;
            $user_btc_address = $user_btc->btc_address;
        }else{
            $real_secret = false;
            $user_btc_address = false;
        }
        $transaction_hash = $this->input->get('transaction_hash');
        $input_address = $this->input->get('input_address');
        $value_in_satoshi = $this->input->get('value');
        $value_in_btc = $value_in_satoshi / 100000000;

        //校验私钥
        if ($real_secret != $this->input->get('secret')){
            exit('Secret incorrect');
        }
        //校验目标地址是否为我的BTC地址
        if ($this->config->item('my_btc_address') != $this->input->get('destination_address')){
            exit('Destination address did not match');
        }
        //校验用户BTC绑定付款地址是否为回调的input_address

        if ($user_btc_address != $input_address){
            exit('Input address did not match');
        }

        //校验transaction_hash状态
        if (strlen($transaction_hash) == 64){
            $hash_status = $this->btc->get_hash_status($transaction_hash);
            if ($hash_status == 'new'){
                $this->btc->new_recharge($_GET);
            }elseif($hash_status == 'used'){
                exit ('Hash expired');
            }
        }else{
            exit('Invalid hash');
        }
        //校验实际收取比特币与回调地址中的value是否相同

        if ($this->_get_receive_payment($transaction_hash) != $value_in_btc){
            exit('Invalid value');
        }

        if ($this->input->get('confirmations') >=4){
            echo "*ok*";
            $this->btc->update_confirmations($transaction_hash,$this->input->get('confirmations'));
            $this->money->add_wallet($this->input->get('uid'),'btc',$value_in_btc,'比特币充值,Hash:'.$transaction_hash);
            //更新用户充值地址
            $this->btc->create_btc_receive_address($this->input->get('uid'));
        }else{
            $this->btc->update_confirmations($transaction_hash,$this->input->get('confirmations'));
            echo 'pending';
        }

    }

    /*
     * 获取我的钱包在$hash中收到的比特币个数
     */
    private  function _get_receive_payment($hash){
        $confirm_url = 'http://blockchain.info/q/txresult/';
        $btc_address = $this->config->item('my_btc_address');
        $res = file_get_contents($confirm_url.$hash.'/'.$btc_address);
        return (floatval($res/100000000));

    }

}