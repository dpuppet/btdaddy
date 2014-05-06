<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cny_method extends CI_Controller {

    /**
     * @var Cny
     */
    public $cny;

    public function __construct(){
        parent::__construct();
        $this->load->model(array('cny','user'));

        $this->user->if_login();
    }

    public function index(){
        $recharge_list = $this->cny->get_recharge_list_html();
        $this->load->view('flat/recharge_cny.tpl',array('recharge_list'=>$recharge_list));
    }


    public function gate_way(){
        $this->load->helper(array('validate','message'));
        $amount = $this->input->post('amount');

        if (($amount = is_valid_number($amount,2)) && $amount>=1){
            $amount = number_fix($amount,2);//强制补全小数点
            $bill_no = $this->cny->create_bill($amount);
            if ($bill_no == FALSE){
                show_msg(0,'系统错误，请重试');
                exit;
            }
            $url = $this->cny->get_recharge_url($bill_no,$amount);
            show_msg(1,$url);
        }else{
            show_msg(0,'数据不正确');
            exit;
        }
    }

    /*
     * 支付结果页
     * Amount:100.00
     * Succeed:88 为成功 其他为失败
     */
    public function status(){
        //var_dump ($GLOBALS['HTTP_RAW_POST_DATA']);
        $amount = $this->input->post('Amount');
        $succeed = $this->input->post('Succeed');
        $status = 'error';
        if ($succeed == '88'){
            $status = "success";
        }
        $this->load->view('flat/recharge_cny_status.tpl',array('status'=>$status,'amount'=>$amount));
    }

}


