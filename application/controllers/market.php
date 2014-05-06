<?php
/**
 * Created by PhpStorm.
 * User: niu
 * Date: 13-12-19
 * Time: 下午11:12
 */

class Market extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('user');
        $this->user->if_login();
    }
    public function index(){
        $this->load->view('flat/dashboard.tpl');
    }
}