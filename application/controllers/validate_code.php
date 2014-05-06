<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate_code extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('code/securimage');
    }

    public function index(){
        $this->securimage->show();
    }
}
