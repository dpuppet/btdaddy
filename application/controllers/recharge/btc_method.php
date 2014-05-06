<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Btc_method extends CI_Controller {

    /**
     * @var Btc
     */
    public $btc;

    public function __construct(){
        parent::__construct();
        $this->load->model(array('btc','user'));

        $this->user->if_login();
    }

    public function index(){

        $recharge_list = $this->btc->get_recharge_list_html();
        $btc_address = $this->btc->get_btc_address();
        $this->load->view('flat/recharge_btc.tpl',array('recharge_list'=>$recharge_list,
                                                        'btc_address'=>$btc_address
                                                       )
        );
    }



}


