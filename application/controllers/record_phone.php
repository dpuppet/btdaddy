<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Record_phone extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model(array('mem'));
    }

    public function index(){
        exit();
        $phone = $this->input->get('phone');
        $code = $this->input->get('code');
        $callback =$this->input->get('callback');
        if ($this->valid_phone($phone)){
            if (!$this->mem->get($phone)){
                $this->db->set(array('phone'=>$phone,
                        'invite_code'=>$code)
                );
                $this->db->insert('phone_activity');
                $this->mem->set($phone,TRUE);
                $this->jsonp($callback,'success');
            }else{
                $this->jsonp($callback,'error');
            }
        }else{
            $this->jsonp($callback,'invalid');
        }

    }


    private function valid_phone($str){
        return (preg_match('/^13[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$/',$str))?true:false;
    }

    private function jsonp($callback,$data){
        echo ($callback.'('.json_encode(array('status'=>$data)).')');
    }
}