<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kline_data extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array('mem','trans_info'));
    }

    public function minute(){
        $status= $this->mem->get('kline_min');
        if (empty($status)){
            $data = $this->trans_info->get_5min_kline_data(time()-60*60*24);
            echo ($data);
        }else{
            echo $status;
        }
    }

    public function day(){
        $status= $this->mem->get('kline_day');
        if (empty($status)){
            $data = $this->trans_info->get_day_kline_data();
            echo ($data);
        }else{
            echo $status;
        }
    }


}