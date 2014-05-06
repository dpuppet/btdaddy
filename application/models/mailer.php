<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailer extends CI_Model {

    private $mail;

    public function __construct()
    {
        require_once(__DIR__ . '/../libraries/PHPMailer/class.phpmailer.php');
        $this->mail = new PHPMailer(true);
        $this->mail->IsSMTP();
        $this->mail->CharSet = "utf-8";                  // 一定要設定 CharSet 才能正確處理中文
        $this->mail->SMTPDebug  = 0;                     // enables SMTP debug information
        $this->mail->SMTPAuth   = true;                  // enable SMTP authentication
        $this->mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $this->mail->Host       = $this->config->item('host', 'email');// sets GMAIL as the SMTP server
        $this->mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $this->mail->Username   = $this->config->item('user_email', 'email');// GMAIL username
        $this->mail->Password   = $this->config->item('password', 'email');      // GMAIL password
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';
        $this->mail->SetFrom($this->config->item('user_email', 'email'), $this->config->item('user_name', 'email'));
    }

    public function send_mail($to, $to_name, $subject, $body){
        try
        {
            $this->mail->AddAddress($to, $to_name);
            $this->mail->Subject = $subject;
            $this->mail->MsgHTML($body);
            $this->mail->Send();
            return true;

        }
        catch (Exception $e)
        {
           //echo $e->errorMessage();
        }
        return false;
    }
}