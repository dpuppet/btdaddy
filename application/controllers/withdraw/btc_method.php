<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class btc_method extends CI_Controller {
    /**
     * @var User
     */
    public $user;


    public function __construct(){
        parent::__construct();
        $this->load->model(array('user','btc'));
        $this->user->if_login();
    }

    public function index(){
        $set_cash_password = ($this->user->if_set_cash_password()==true)?TRUE:FALSE;
        $record = $this->btc->get_withdraw_list_html();

        $user_cash = $this->user->wallet();
        $btc = $user_cash->btc;//用户数据库内比特币数量
        $btc = substr_replace($btc,'',strpos($btc, '.') + 5);
        $this->load->view('flat/withdraw_btc.tpl',array('btc'=>$btc,
                                                        'withdraw_list'=>$record)
        );
    }



    /*
     * 处理提现申请
     */
    public function do_withdraw(){
        $this->load->model('code/securimage');
        $this->load->helper(array('message','validate'));
        $code = $this->input->post('code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }

        //校验现金密码
        if ($this->user->if_set_cash_password()){
            $cash_password = $this->input->post('cash_password');
            if ( ! $this->user->validate_cash_password($cash_password)){
                show_msg(0,'现金密码不正确');
                exit();
            }

        }else{
            show_msg(0,'你还未设置现金密码，不可进行提现操作');
            exit();
        }

        $amount = $this->input->post('amount');
        if ((!($amount = is_valid_number($amount,4))) || ($amount <0.01) ){
            show_msg(0,'金额不正确');
            exit();
        }

        $fee = $amount*0.05 +5;
        $user_cash = $this->user->wallet();
        $btc = $user_cash->btc;//用户数据库内比特币数量
        if ($amount+$fee > $btc){
            show_msg(0,'余额不足');
            exit();
        }


        $btc_address = htmlentities(trim($this->input->post('btc_address')),ENT_QUOTES,'UTF-8');

        if (strlen($btc_address) < 10 ){
            show_msg(0,'钱包地址格式不正确');
            exit();
        }
        if (empty($btc_address) || empty($amount)){
            show_msg(0,'请填写完整信息');
            exit();
        }

        $status = $this->btc->create_withdraw($amount,$btc_address);
        if ($status){
            show_msg(1,'申请已受理，工作人员会在24小时内确认您的请求');
            exit();
        }else{
            show_msg(0,'系统错误，请稍后重试');
            exit();
        }
    }

}