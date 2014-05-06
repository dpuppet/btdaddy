<?php
class User extends CI_Model {

    public function __construct(){
         parent::__construct();
    }

    /*
     * 获取用户钱包信息
     */
    public function wallet(){
        $this->db->where('user_id',$_SESSION['uid']);
        $result = $this->db->get('user_wallet');
        return $result->row();
    }

    /*
     * 创建新用户
     * 如果已注册则返回错误消息
     */
    public function signup($email,$password){
        $this->db->where('email',$email);
        $res = $this->db->get('users');
        if ($res->num_rows()>0){
            return false;
        }
        $this->db->set(array('email'=>$email,
                             'password'=>sha1($password),
                             'reg_ip'=>$_SERVER['REMOTE_ADDR']
                            )
        );
        $this->db->insert('users');
        $id = $this->db->insert_id();
        $this->_set_login($this->db->insert_id(),$email);
        $this->db->set('user_id',$this->db->insert_id());
        $this->db->insert('user_wallet');
        $this->db->set('user_id',$id);
        $this->db->insert('user_bind_address');
        return true;
    }

    /*
     * 尝试登陆账户，如果失败则返回错误信息
     */
    public function try_login($email,$password){
        $this->db->where(array('email'=>$email,
                               'password'=>$password
                              )
        );
        $res = $this->db->get('users');
        if ($res->num_rows() > 0){
            $this->_set_login($res->row()->id,$email);
            return true;
        }
        return false;
    }

    private function _set_login($uid,$email){
        $_SESSION['login'] = TRUE;
        $_SESSION['uid'] = $uid;
        $_SESSION['email'] = $email;
    }

    public function send_email($email)
    {
        $this->db->where('email',$email);
        $res = $this->db->get('users');
        if ($res->num_rows() <= 0){
            return false;
        }

        $token = md5(rand(100000,999999) . time());
        $expire = 30*60;

        $this->load->model('mem');
        $this->mem->set_by_time($token, $email, $expire);

        $mail_username = $res->row()->username;

        //todo
        $mail_subject = '立刻修改密码';
        $mail_body = '<a href="https://www.btdaddy.com/reset/password?token='.$token.'">立刻修改密码</a>';
        $this->load->model('mailer');
        return $this->mailer->send_mail(
            $email,
            $mail_username,
            $mail_subject,
            $mail_body
        );
    }

    public function reset_password($token, $password)
    {
        $this->load->model('mem');
        $email = $this->mem->get($token);
        if(!empty($email))
        {
            $this->db->where('email',$email);
            $res = $this->db->get('users');
            if ($res->num_rows() > 0){
                $this->db->set(array('password'=>sha1($password)));
                $this->db->update('users');
                $this->mem->delete($token);
                return true;
            }
        }
        return false;
    }

    /**
     * 检测用户是否登录，如果未登录则跳转至登录页面
     */
    public function if_login(){
        $this->load->helper('url');
        if (!isset($_SESSION['login']) || !isset($_SESSION['uid'])){
            redirect('../login');
            exit();
        }
    }

    /*
     * 检测用户是否已设置现金密码
     */
    public function if_set_cash_password(){
        $this->db->where('id',$_SESSION['uid']);
        $data = $this->db->get('users');
        $data = $data->row();
        if ($data->cash_password == ''){
            return false;
        }else{
            return true;
        }
    }

    /*
     * 校验用户现金密码是否正确
     */
    public function validate_cash_password($password){
        $password = sha1($password);
        $this->db->where('id',$_SESSION['uid']);
        $data = $this->db->get('users');
        $data = $data->row();
        if ($data->cash_password == $password){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 更改用户现金密码
     */
    public function change_cash_password($old_password,$new_password){
        $this->db->where('id',$_SESSION['uid']);
        $data = $this->db->get('users');
        $data = $data->row();

        if (($data->cash_password == '')  || ($data->cash_password == sha1($old_password))){
            $this->db->where('id',$_SESSION['uid']);
            $this->db->set('cash_password',sha1($new_password));
            $this->db->update('users');
            return true;
        }else{
            return false;
        }
    }

    /*
     * 更改用户登陆密码
     */
    public function change_login_password($old_password,$new_password){
        $this->db->where('id',$_SESSION['uid']);
        $data = $this->db->get('users');
        $data = $data->row();
        if (($data->password == sha1($old_password))){
            $this->db->where('id',$_SESSION['uid']);
            $this->db->set('password',sha1($new_password));
            $this->db->update('users');
            return true;
        }else{
            return false;
        }
    }
}