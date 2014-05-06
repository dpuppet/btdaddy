<?php
/**
 * Created by PhpStorm.
 * User: niu
 * Date: 13-12-19
 * Time: 下午6:58
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Safe extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('user');
        $this->load->helper('message');
        $this->user->if_login();
    }

    public function index(){


    }


    public function cash_password(){
        $this->load->view('flat/cash_password.tpl');
    }

    public  function change_cash_password(){
        $this->load->library('validation');
        $this->load->model('code/securimage');

        $code = $this->input->post('code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }
        $password = trim($this->input->post('old_password'));
        $new_password = trim($this->input->post('new_password'));
        if (!$this->validation->password_validator($new_password)){
            show_msg(0,'新密码必须是6位以上且同时包含数字与字母');
            exit;
        }
        if ($this->user->change_cash_password($password,$new_password)){
            show_msg(1,'资金密码修改成功');
            exit;
        }else{
            show_msg(0,'原始密码不正确');
            exit;
        }
    }

    public function login_password(){
        $this->load->view('flat/login_password.tpl');
    }

    public function change_login_password(){
        $this->load->library('validation');
        $this->load->model('code/securimage');

        $code = $this->input->post('code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }

        $password = trim($this->input->post('old_password'));
        $new_password = trim($this->input->post('new_password'));
        if (!$this->validation->password_validator($new_password)){
            show_msg(0,'新密码必须是6位以上且同时包含数字与字母');
            exit;
        }
        if ($this->user->change_login_password($password,$new_password)){
            show_msg(1,'登陆密码修改成功');
            session_destroy();
            exit;
        }else{
            show_msg(0,'原始密码不正确');
            exit;
        }
    }

}