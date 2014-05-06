<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cny_notify extends CI_Controller {

    /**
     * @var Cny
     */
    public $cny;

    public function __construct(){
        parent::__construct();
        $this->load->model(array('cny','money','user','log'));

    }

    public function index(){
        $bill_no = $this->input->post('BillNo');
        $amount = $this->input->post('Amount');
        $succeed = $this->input->post('Succeed');
        $result = $this->input->post('Result');
        $sign_info = strtoupper($this->input->post('SignMD5info'));

        $secret= $this->config->item('md5_key','payment');
        $real_sign = strtoupper(md5($bill_no.'&'.$amount.'&'.$succeed.'&'.$secret));

        //校验签名
        if ($sign_info != $real_sign){
            echo ('fail sign');
            exit;
        }

        //校验流水单状态以及钱数是否与数据库中符合
        if ($this->cny->get_bill_status($bill_no,$amount) == false){
            echo ('fail bill');
            exit;
        }

        if ($succeed != '88'){
            echo ('fail');
            $succeed = intval($succeed);
            $this->cny->update_bill($bill_no,$succeed,$result);
            exit;
        }

        if ($this->cny->confirm_bill($bill_no,$amount,$result)){
            echo('ok');
        }else{
            echo('fail');
        }
    }
}