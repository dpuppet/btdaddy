<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('validate','message'));
        $this->load->model(array('user','transaction','trans_info'));
        $this->user->if_login();
    }



    public function ask(){
        $this->load->model('order');
        $user_cash = $this->user->wallet();
        $btc = $user_cash->btc;//用户数据库内人民币数量
        $btc = substr_replace($btc,'',strpos($btc, '.') + 5);
        $this->load->view('flat/trade_ask.tpl',array('btc'=>$btc,
                                                     'depth'=>$this->trans_info->depth_html(),
                                                     'best_price'=>$this->mem->get('lowest_ask_price'),
                                                     'order_list'=>$this->order->get_ask_list_html()
                                                    )
        );
    }
    /*
     * 卖方托管订单
     */
    public function do_ask(){
        if (!isset($_SESSION['login'])){
            show_msg(0,'请登录后进行操作');
            exit;
        }
        $type = $this->input->post('type');
        $number = $this->input->post('amount');
        $price =  $this->input->post('price');


        if (($number = is_valid_number($number,4)) && ($price = is_valid_number($price,2)) && ($type = is_valid_cash_type($type))){
            $user_cash = $this->user->wallet();
            $coin = $user_cash->$type;//用户数据库内对应类型钱币数量

            if ($coin - $number<0){
                show_msg(0,'余额不足');
                exit;
            }else{
                $trans_ok = $this->transaction->new_ask_ticket($type,$number,$price);
                if ($trans_ok === TRUE){
                    show_msg(1,'委托成功');
                }else{
                    if ($trans_ok == 'exceed'){
                        show_msg(0,'超出限制，最多可同时委托5单');
                    }else{
                        show_msg(0,'系统错误，请稍后再试');
                    }
                }
            }
        }else{
                show_msg(0,'请输入正确的数据');
                exit;
        }

    }

    public function bid(){
        $this->load->model('order');
        $user_cash = $this->user->wallet();
        $cny = $user_cash->cny;//用户数据库内人民币数量
        $cny = substr_replace($cny,'',strpos($cny, '.') + 3);
        $this->load->view('flat/trade_bid.tpl',array('cny'=>$cny,
                                                     'depth'=>$this->trans_info->depth_html(),
                                                     'best_price'=>$this->mem->get('highest_bid_price'),
                                                     'order_list'=>$this->order->get_bid_list_html()
                                                    )
        );
    }

    /*
     * 买方托管订单
     */
   public function do_bid(){
       if (!isset($_SESSION['login'])){
           show_msg(0,'请登录后进行操作');
           exit;
       }
       $type = $this->input->post('type');
       $number = $this->input->post('amount');
       $price =  $this->input->post('price');
       if (($number = is_valid_number($number,4)) && ($price = is_valid_number($price,2)) && ($type = is_valid_cash_type($type))){
           $user_cash = $this->user->wallet();
           $cny = $user_cash->cny;//用户数据库内人民币数量

           if ($cny - ($number * $price)<0){
               show_msg(0,$cny - ($number * $price));
               show_msg(0,'余额不足');
               exit;
           }else{
               $trans_ok = $this->transaction->new_bid_ticket($type,$number,$price);
               if ($trans_ok === TRUE){
                   show_msg(1,'委托成功');
               }else{
                   if ($trans_ok == 'exceed'){
                       show_msg(0,'超出限制，最多可同时委托5单');
                   }else{
                       show_msg(0,'系统错误，请稍后再试');
                   }
               }
           }

       }else{
           show_msg(0,'请输入正确的数据');
           exit;
       }
   }



   public function manage_trade(){
       $this->load->model('order');
       $ask_list = $this->order->get_ask_list_html_for_manage();
       $bid_list = $this->order->get_bid_list_html_for_manage();
       $this->load->view('flat/trade_manage.tpl',array('ask_list'=>$ask_list,
                                                       'bid_list'=>$bid_list
                                                      )
       );
   }

   public function cancel_trade(){
       if (!isset($_SESSION['login'])){
           show_msg(0,'请登录后进行操作');
           exit;
       }
       $id = intval($this->input->post('id'));
       $type = $this->input->post('type');
       if ($type != 'ask' && $type != 'bid'){
           show_msg(0,'数据非法');
           exit;
       }
       if ($this->transaction->cancel_ticket($id,$type)){
           show_msg(1,'success');
       }else{
           show_msg(0,'系统繁忙，请稍后再试');
       }
   }


}
