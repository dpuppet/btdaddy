<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recent_deals extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('mem');
    }

    public function index(){
        echo $this->mem->get('btc_success_record');
    }

    public function dashboard(){
        $status = $this->mem->get('recent_deals');
        if (!empty($status)){
            echo ($status);
        }else{
            $bid_data ='';$ask_data='';$success_data = '';
            $bid_orders = $this->mem->get('bid_btc_orders');
            $ask_orders = $this->mem->get('ask_btc_orders');
            $success_orders = $this->mem->get('btc_success_record');
            $bid_orders = json_decode($bid_orders);
            $ask_orders = json_decode($ask_orders);
            $success_orders = json_decode($success_orders);

            //把JSON数据格式化为HTML
            foreach($bid_orders as $row){
                $bid_data .= '<tr><td>￥'.round($row[0],2).
                              '</td><td class="center">'
                               .round($row[1],4).'</td>
                                    <td class="center">
                                        <span class="label label-success">买入</span>
                                    </td></tr>';
            }

            foreach($ask_orders as $row){
                $ask_data .= '<tr><td>￥'.round($row[0],2).
                    '</td><td class="center">'
                    .round($row[1],4).'</td>
                                    <td class="center">
                                        <span class="label label-important">卖出</span>
                                    </td></tr>';
            }

            foreach($success_orders as $row){
                $type = $row[2]=='ask'?'<span class="label label-important">卖出</span></td>':'<span class="label label-success">买入</span></td>';
                $success_data .= '<tr><td>￥'.round($row[0],2).'</td>
                                    <td class="center">'.round($row[1],4).'</td>
                                    <td class="center">'.$type.'<td class="center">'.$row[3].'</td></tr>';
            };


            $data = json_encode(array('bid'=>$bid_data,
                                   'ask'=>$ask_data,
                                   'deals'=>$success_data)
                            );
            $this->mem->set_by_time('recent_deals',$data,10);
            echo $data;
        }

    }


    /**
     * 返回
     * 最新成交价、买方最高价、卖方最低价
     * 卖方最高价、买方最低价
     * 今日成交量
     */
    public function real_time_price(){
        $newest_deal_price = $this->mem->get('newest_deal_price');
        $bid1 = $this->mem->get('highest_bid_price');
        $ask1 = $this->mem->get('lowest_ask_price');
        $highest_price = $this->mem->get('highest_price');
        $lowest_price = $this->mem->get('lowest_price');
        $key = date('Y-m-d',time()).'_amount';
        $amount = $this->mem->get($key);
        echo(json_encode(array('newest_deal_price'=>$newest_deal_price,
                               'bid1'=>$bid1,
                               'ask1'=>$ask1,
                               'highest_price'=>$highest_price,
                               'lowest_price'=>$lowest_price,
                               'today_amount'=>round($amount,4))

        ));
    }
}