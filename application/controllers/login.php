<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model(array('code/securimage','user'));
        $this->load->helper(array('message','url'));
    }

    public function index(){
        if (isset($_SESSION['login'])){
            redirect($this->config->item('dashboard'));
        }
        $this->load->view('signin.tpl');
    }

    public function logout(){
        session_destroy();
        redirect('../');
    }


    public function do_signin(){
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));
        $code = $this->input->post('validate_code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }
        if (!$this->valid_email($email)){
            show_msg(0,'邮箱格式错误');
            exit();
        }
        if (!$this->user->try_login($email,$password)){
            show_msg(0,'邮箱或密码错误');
            exit();
        }
        show_msg(1,'登陆成功');

    }

    private function valid_email($str){
        return (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/',$str))?true:false;
    }
}