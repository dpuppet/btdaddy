<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cny_method extends CI_Controller {
    /**
     * @var User
     */
    public $user;


    public function __construct(){
        parent::__construct();
        $this->load->model(array('user','cny'));
        $this->user->if_login();
    }

    public function index(){
        $set_cash_password = ($this->user->if_set_cash_password()==true)?TRUE:FALSE;
        $record = $this->cny->get_withdraw_list_html();

        $user_cash = $this->user->wallet();
        $cny = $user_cash->cny;//用户数据库内人民币数量
        $cny = substr_replace($cny,'',strpos($cny, '.') + 3);
        $this->load->view('flat/withdraw_cny.tpl',array('cny'=>$cny,
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
        if (!($amount = is_valid_number($amount,2))){
            show_msg(0,'金额不正确');
            exit();
        }

        $fee = $amount*0.05 +5;
        $user_cash = $this->user->wallet();
        $cny = $user_cash->cny;//用户数据库内人民币数量
        if ($amount+$fee > $cny){
            show_msg(0,'余额不足');
            exit();
        }

        $method = htmlentities(trim($this->input->post('method')),ENT_QUOTES,'UTF-8');
        $card_no = htmlentities(trim($this->input->post('card_no')),ENT_QUOTES,'UTF-8');
        $card_name = htmlentities(trim($this->input->post('card_name')),ENT_QUOTES,'UTF-8');
        $card_addr = htmlentities(trim($this->input->post('card_addr')),ENT_QUOTES,'UTF-8');

        if (strlen($card_no) < 10 ){
            show_msg(0,'银行卡号格式不正确');
            exit();
        }
        if (empty($method) || empty($card_name) || empty($card_addr)){
            show_msg(0,'请填写完整信息');
            exit();
        }

        $status = $this->cny->create_withdraw($amount,$method,$card_no,$card_addr,$card_name);
        if ($status){
            show_msg(1,'提现申请已受理，工作人员会在1到2个工作日内确认');
            exit();
        }else{
            show_msg(0,'系统错误，请稍后重试');
            exit();
        }
    }

}