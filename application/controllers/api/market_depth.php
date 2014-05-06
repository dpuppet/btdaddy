<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Market_depth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('mem');
    }

    public function index(){
        $bid_depth = json_decode($this->mem->get('bid_depth'),TRUE);
        $i=4;
        foreach ($bid_depth as $row){
            $data[$i]['price'] = $row['price'];
            $data[$i]['num'] = $row['num'];
            $i--;
        }
        $ask_depth = json_decode($this->mem->get('ask_depth'),TRUE);
        $i=5;
        foreach ($ask_depth as $row){
            $data[$i]['price'] = $row['price'];
            $data[$i]['num'] = $row['num'];
            $i++;
        }
        for ($i=0;$i<10;$i++){
            $out_arr[] = $data[$i];
        }

        echo(json_encode($out_arr));

    }

    /*
     * 输出列表HTML
     */
    public function html(){
        $this->load->model('trans_info');
        echo $this->trans_info->depth_html();

    }
}