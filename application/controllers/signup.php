<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('message','url'));
        $this->load->model('code/securimage');
        if (isset($_SESSION['login'])){
            redirect($this->config->item('dashboard'));
        }
    }

    public function index(){
        $this->load->view('signup.tpl');
    }

    public function do_signup(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $code = $this->input->post('validate_code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }
        if (!$this->valid_email($email)){
            show_msg(0,'邮箱格式错误');
            exit();
        }
        if (strlen($password)<6){
            show_msg(0,'密码最少6位');
            exit();
        }
        $this->load->model('user');
        if (!$this->user->signup($email,$password)){
            show_msg(0,'该邮箱已注册');
            exit();
        }
        show_msg(1,'注册成功');
    }


    private function valid_email($str){
        return (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/',$str))?true:false;
    }
}