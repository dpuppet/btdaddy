<?php
class Transaction extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model(array('log','mem'));
    }

    /*
     * 生成新的卖方委托单
     * 并从用户钱包数据库里减去对应币种$number个金额
     */
    public function new_ask_ticket($type,$number,$price){
        $this->db->select('count(*) as count_num',FALSE);
        $this->db->where(array('type'=>$type,
                               'user_id'=>$_SESSION['uid'],
                               'status'=>'0')
        );
        $data = $this->db->get('ask_ticket');
        if ($data->num_rows()>0){
            if ($data->row()->count_num >= 5){
                return 'exceed';
            }
        }
        $this->db->trans_start();//执行严格事务，有一条错误则回滚
            //减去用户钱包中的金额
            $this->db->set($type,$type.'-'.$number,FALSE);
            $this->db->where('user_id',$_SESSION['uid']);
            $this->db->update('user_wallet');
            //生成卖方委托单
            $this->db->set(array(
                                 'user_id'=>$_SESSION['uid'],
                                 'num'=>$number,
                                 'available_num'=>$number,
                                 'price'=>$price,
                                 'type'=>$type,
                                 'status'=>0,
                                 'time'=>date("Y-m-d H:i:s")
                                )
            );
            $this->db->insert('ask_ticket');
            $id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            $this->_new_order_record($type,$price,$number,'ask');
            $this->log->log_wallet($_SESSION['uid'],$type,'outcome',$number,'委托卖单：'.$id);
            return TRUE;
        }
    }


    /*
     * 生成新的买方委托单
     * 同时减去用户钱包中对应人民币数量
     */
    public function new_bid_ticket($type,$number,$price){
        $this->db->select('count(*) as count_num',FALSE);
        $this->db->where(array('type'=>$type,
                               'user_id'=>$_SESSION['uid'],
                               'status'=>'0')
                        );
        $data = $this->db->get('bid_ticket');
        if ($data->num_rows()>0){
            if ($data->row()->count_num >= 5){
                return 'exceed';
            }
        }

        $this->db->trans_start();//执行严格事务，有一条错误则回滚
            //减去用户钱包中的对应数量人民币
            $this->db->set('cny','cny-'.$number*$price,FALSE);
            $this->db->where('user_id',$_SESSION['uid']);
            $this->db->update('user_wallet');
            //生成买方委托单
            $this->db->set(array(
                                    'user_id'=>$_SESSION['uid'],
                                    'num'=>$number,
                                    'available_num'=>$number,
                                    'price'=>$price,
                                    'type'=>$type,
                                    'status'=>0,
                                    'time'=>date("Y-m-d H:i:s")
                                 )
            );
            $this->db->insert('bid_ticket');
            $id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            $this->_new_order_record($type,$price,$number,'bid');
            $this->log->log_wallet($_SESSION['uid'],'cny','outcome',$number,'委托买单：'.$id);
            return TRUE;

        }
    }

    /*
     * 取消用户委托单
     * 同时将未完成交易的部分返回用户钱包
     * @t_type 交易类型 bid 或 ask
     */
    public function cancel_ticket($id,$t_type){
        //如果交易撮合脚本正在运行，则返回错误
        if ($this->mem->get('is_running_script') == 'running'){
            return FALSE;
        }
        $this->load->model('money');
        $table = $t_type.'_ticket';
        //校验操作合法性
        $this->db->where('id',$id);
        $data = $this->db->get($table);
        if ($data->num_rows()>0){
            if ($_SESSION['uid'] != $data->row()->user_id){
                return FALSE;
            }
            if ($data->row()->status != 0){
                return FALSE;
            }
        }else{
            return FALSE;
        }


        $available_num = $data->row()->available_num;
        $price = $data->row()->price;
        $this->db->trans_start();
        $this->db->set('status',2);
        $this->db->where('id',$id);
        $this->db->update($table);

        switch ($t_type){
            case 'ask':
                $this->money->add_wallet($_SESSION['uid'],$data->row()->type,$available_num,'取消卖单委托：'.$id);
                break;
            case 'bid':
                $this->money->add_wallet($_SESSION['uid'],'cny',$available_num*$price,'取消买单委托：'.$id);
                break;
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            return TRUE;
        }
    }


    /*
     * 按出价从低到高获取所有有效的卖方托管单
     */
    public function get_available_asks(){
        //select * from ask_ticket where status = 0 ORDER BY type,price asc
        $this->db->where('status',0);
        $this->db->where('user_id !=','10002');//hacked，不提取拟合账号的托管单
        $this->db->order_by('type,price','ASC');
        $result =$this->db->get('ask_ticket');
        if ($result->num_rows() == 0){
            return false;
        }else{
            //更新卖1价格
            $this->mem->set('lowest_ask_price',round($result->row()->price,2));
            //更新最高价格
            $this->mem->set('highest_price',round($result->row($result->num_rows()-1)->price,2));
            $i = 0;
            foreach($result->result_array() as $row){
                $arr[]=$row;
                //更新排名前5卖价
                if ($i<5){
                    $i++;
                    $data[]=array('price'=>round($row['price'],2),
                        'num'=>round($row['available_num'],4)
                    );

                }
            }
            if ($i==5){
                $this->mem->set('ask_depth',json_encode($data));
            }
        }
        return $arr;
    }

    /*
     * 按出价从高到低获取所有有效的买方托管单
     */
    public function get_available_bids(){
        //select * from bid_ticket where status = 0 ORDER BY type,price desc
        $this->db->where('status',0);
        $this->db->where('user_id !=','10003');//hacked，不提取拟合账号的托管单
        $this->db->order_by('type,price','DESC');
        $result =$this->db->get('bid_ticket');
        if ($result->num_rows() == 0){
            return false;
        }else{
            //更新买1价格
            $this->mem->set('highest_bid_price',round($result->row()->price,2));
            //更新最低价格
            $this->mem->set('lowest_price',round($result->row($result->num_rows()-1)->price,2));
            $i=0;
            foreach($result->result_array() as $row){
                $arr[]=$row;
                //更新排名前5买价
                if ($i<5){
                    $i++;
                    $data[]=array('price'=>round($row['price'],2),
                                  'num'=>round($row['available_num'],4)
                    );
                }
            }
            if ($i==5){
                $this->mem->set('bid_depth',json_encode($data));
            }
        }
        return $arr;

    }

    /*
     * 撮合交易，并生成交易流水单
     */
    public function new_transaction($bid_ticket,$ask_ticket){
        $this->load->model('money');


        $ask_num = $ask_ticket['available_num'];
        $bid_num = $bid_ticket['available_num'];
        $ask_id = $ask_ticket['id'];
        $bid_id = $bid_ticket['id'];

        $return_arr = array(
                            'ask_status'=>$ask_ticket['status'],
                            'bid_status'=>$bid_ticket['status'],
                            'num'=>($ask_num>$bid_num)?$bid_num:$ask_num,
                            'ask_available'=>0,
                            'bid_available'=>0
        );

        if ($ask_ticket['status'] != 0 || $bid_ticket['status'] !=0){
            return false;
        }

        if ($bid_num > $ask_num){
            $this->_close_ticket($ask_id,'ask');
            $this->_update_ticket($bid_id,$ask_num,'bid');
            $this->_create_transaction_ticket($ask_num,$bid_ticket['price'],$ask_ticket['price'],$bid_id,$ask_id,$ask_ticket['type'],'ask');
            $return_arr['ask_status'] = 1;
            $return_arr['ask_available'] = 0;
            $return_arr['bid_available'] = $bid_num - $ask_num;
        }elseif ($bid_num < $ask_num){
            $this->_close_ticket($bid_id,'bid');
            $this->_update_ticket($ask_id,$bid_num,'ask');
            $this->_create_transaction_ticket($bid_num,$bid_ticket['price'],$ask_ticket['price'],$bid_id,$ask_id,$ask_ticket['type'],'bid');
            $return_arr['bid_status'] = 1;
            $return_arr['bid_available'] = 0;
            $return_arr['ask_available'] = $ask_num - $bid_num;
        }else{
            $this->_close_ticket($ask_id,'ask');
            $this->_close_ticket($bid_id,'bid');
            $this->_create_transaction_ticket($ask_num,$bid_ticket['price'],$ask_ticket['price'],$bid_id,$ask_id,$ask_ticket['type'],'ask&bid');
            $return_arr['bid_status']=1;
            $return_arr['ask_status']=1;
        }
        //将成交成功的部分返还回用户钱包
        $this->money->add_wallet($bid_ticket['user_id'],$bid_ticket['type'],$return_arr['num'],'买入'.$bid_ticket['type'].'交易单'.$bid_ticket['id']);
        $this->money->add_wallet($ask_ticket['user_id'],'cny',$return_arr['num'] * $ask_ticket['price'],'卖出'.$ask_ticket['type'].'交易单'.$ask_ticket['id']);



        return $return_arr;

    }

    /*
     * 关闭委托单
     */
    private function _close_ticket($id,$t_type){
        $this->db->set(array(
                            'available_num'=>0,
                            'status'=>1
                            )
        );
        $this->db->where('id',$id);
        $this->db->update($t_type.'_ticket');

    }

    /*
     * 更新委托单有效数量
     */
    private function _update_ticket($id,$t_num,$t_type){
        $this->db->where('id',$id);
        $this->db->set('available_num','available_num-'.$t_num,false);
        $this->db->update($t_type.'_ticket');
    }

    /*
     * 创建交易流水单
     */
    private function _create_transaction_ticket($num,$bid_price,$ask_price,$bid_id,$ask_id,$type,$trans_type){
        $weighting = floatval(rand(1000,4000)/10000);
        $time = time();
        $this->db->set(array('num'=>$num,
                             'bid_price'=>$bid_price,
                             'ask_price'=>$ask_price,
                             'bid_ticket'=>$bid_id,
                             'ask_ticket'=>$ask_id,
                             'type'=>$type,
                             'premium'=>$num*($bid_price-$ask_price),
                             'transaction_type'=>$trans_type,
                             'weighting'=>$weighting
                            )
        );
        $this->db->insert('transaction');
        //记录K线图数据
        $this->_create_kline($time,$type,$bid_price,$num+$weighting);
        //加入向前台展示的最近交易公开记录队列
        if ($trans_type == 'ask&bid'){
            $trans_type = 'bid';
        }
        //更新最新成交价格
        $this->mem->set('newest_deal_price',round($ask_price,4));
        //更新今日成交总量
        $key = date('Y-m-d',time()).'_amount';
        $amount = $this->mem->get($key);
        $amount += $num + $weighting;
        $this->mem->set($key,$amount);

        $this->_new_success_record($type,$bid_price,$num+$weighting,$trans_type);
    }

    /**
     * 记录K线图5分钟记录
     */
    private function _create_kline($time,$type,$bid_price,$num){
        $key = date('i',$time) - (date('i', $time)%5);
        $k_time = date('Y-m-d H:'.$key.':00', $time);

        $this->db->where(array('k_time'=>$k_time,
                'type'=>$type
            )
        );
        $exist = $this->db->get('kline_min');
        if($exist->num_rows > 0)
        {
            $highest = $exist->row()->high;
            $lowest = $exist->row()->low;
            $this->db->set('close', $bid_price);
            if($bid_price >  $highest)
            {
                $this->db->set('high', $bid_price);
            }

            if($bid_price <  $lowest)
            {
                $this->db->set('low', $bid_price);
            }

            $this->db->set('num','num+'.$num,false);
            $this->db->where('k_time', $k_time);
            $this->db->update('kline_min');
        }
        else//当前时间段的k线图数据不存在则创建
        {
            $this->db->set(array('k_time'=>$k_time,
                    'type'=>$type,
                    'open'=>$bid_price,
                    'close'=>$bid_price,
                    'high'=>$bid_price,
                    'low'=>$bid_price,
                    'num'=>$num
                )
            );
            $this->db->insert('kline_min');
        }

        //更新日K线图数据
        $k_time = date('Y-m-d 00:00:00',$time);
        $this->db->where(array('k_time'=>$k_time,
                               'type'=>$type
            )
        );
        $exist = $this->db->get('kline_day');
        if($exist->num_rows > 0)
        {
            $highest = $exist->row()->high;
            $lowest = $exist->row()->low;
            $this->db->set('close', $bid_price);
            if($bid_price >  $highest)
            {
                $this->db->set('high', $bid_price);
            }

            if($bid_price <  $lowest)
            {
                $this->db->set('low', $bid_price);
            }

            $this->db->set('num','num+'.$num,false);
            $this->db->where('k_time', $k_time);
            $this->db->update('kline_day');
        }
        else//当前日期的k线图数据不存在则创建
        {
            $this->db->set(array('k_time'=>$k_time,
                    'type'=>$type,
                    'open'=>$bid_price,
                    'close'=>$bid_price,
                    'high'=>$bid_price,
                    'low'=>$bid_price,
                    'num'=>$num
                )
            );
            $this->db->insert('kline_day');
        }
    }

    /**
     * 加入向前台显示的最新成交记录缓存队列
     * btc_success_record
     * 价格、数量、买或卖、时间
     */
    private function _new_success_record($type,$price,$num,$trans_type){


        $key = $type.'_success_record';
        $len_limit = 50;
        $num = round($num,4);
        $price = round($price,2);
        $success_record = $this->mem->get($key);
        if (empty($success_record)){
            $data[]=array($price,$num,$trans_type,date('H:i:s', time()));
            $this->mem->set_by_time($key,json_encode($data),0);
        }else{
            $data = json_decode($success_record,TRUE);
            array_unshift($data,array($price,$num,$trans_type,date('H:i:s', time())));
            if (count($data) > $len_limit){
                array_pop($data);
            }
            $this->mem->set_by_time($key,json_encode($data),0);
        }

    }

    /**
     * 加入向前台显示的最新委托单记录缓存队列
     * ask_btc_orders
     * bid_btc_orders
     * 价格，数量
     */
    private function _new_order_record($type,$price,$num,$trans_type){

        $this->load->model('mem');
        $key = $trans_type.'_'.$type.'_orders';
        $len_limit = 50;
        $num = round($num,4);
        $price = round($price,2);
        $orders = $this->mem->get($key);

        if (empty($orders)){
            $data[]=array($price,$num);
            $this->mem->set_by_time($key,json_encode($data),0);
        }else{
            $data = json_decode($orders,true);
            array_unshift($data,array($price,$num));
            if (count($data) > $len_limit){
                array_pop($data);
            }
            $this->mem->set_by_time($key,json_encode($data),0);
        }

    }

}