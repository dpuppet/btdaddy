<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('validate','message'));
        $this->load->model(array('user','transaction','trans_info'));
        $this->user->if_login();
    }



    public function index(){
        $this->load->view('flat/my.tpl');
    }

}
