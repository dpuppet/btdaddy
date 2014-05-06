<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('code/securimage','user'));
        $this->load->helper(array('message','url'));
        $this->load->library('validation');
    }

    public function index(){

        $this->load->view('reset.tpl');
    }

    public function do_reset(){
        $email = $this->input->post('email');
        $code = $this->input->post('validate_code');
        if ($this->securimage->check($code) == false){
            show_msg(0,'验证码错误');
            exit();
        }
        if (!$this->valid_email($email)){
            show_msg(0,'邮箱格式错误');
            exit();
        }

        if($this->user->send_email($email))
        {
            show_msg(1,'发送邮件成功,请查看邮件');
            exit();
        }
        show_msg(0,'发送失败，无效的用户邮箱');
    }

    private function valid_email($str){
        return (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/',$str))?true:false;
    }

    public function password()
    {
        $token = $this->input->get('token');
        if(empty($token))
        {
            echo 'the token is invalid!';
            exit;
        }
        $data = array(
            'token' => $token,
        );
        $this->load->view('reset_password.tpl', $data);
    }

    public function do_password()
    {
        $token = $this->input->post('password_token');
        if(empty($token))
        {
            show_msg(0,'无效的找回密码链接，请重新发送密码找回邮件');
            exit;
        }

        $new_password = $this->input->post('new_password');
        $confirm_new_password = $this->input->post('confirm_new_password');

        if(!$this->validation->password_validator($new_password))
        {
            show_msg(0,'密码必须同时包含数字和字母');
            exit;
        }
        if($confirm_new_password == $new_password)
        {
            if($this->user->reset_password($token, $new_password))
            {
                show_msg(1,'密码修改成功');
                exit;
            }
            show_msg(0,'密码修改失败，请重新发送密码找回邮件');
            exit;
        }
        show_msg(0,'新密码不一致');
        exit;

    }

    function flush(){
        $this->load->model('mem');
        $this->mem->flush();
    }
}