<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


    public function __construct(){
        parent::__construct();
        //$this->load->model('mem');
    }
	public function index()
	{
        if (isset($_SESSION['uid'])){
            $this->load->model(array('user','order'));
            $user_cash = $this->user->wallet();
            $transanction_num = $this->order->get_transaction_num(1);
            $available_num = $this->order->get_transaction_num(0);
            $cny = round($user_cash->cny,2);
            $btc = round($user_cash->btc,4);
            $this->load->view('flat/my.tpl',array('cny'=>$cny,'btc'=>$btc,'transaction_num'=>$transanction_num,'available_num'=>$available_num));
        }else{
            $newest_deal_price = $this->mem->get('newest_deal_price');
            $this->load->view('landing_page.tpl',array('price'=>$newest_deal_price));
        }
    }

}
