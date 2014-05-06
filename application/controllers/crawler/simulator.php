<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 自动拟合市场价格
 * purpose：在站点没有交易额的情况下，抓取市场价格，自动生成虚拟交易信息。
 */

class Simulator extends CI_Controller {
    public  function __construct(){
        parent::__construct();
        $this->load->model(array('transaction','mem','log'));
    }


    public function index(){
        $data = file_get_contents('http://www.btc123.com/e/interfaces/tickers.js?type=fxbtcTicker&s='.rand(10000,99999));
        $json = json_decode($data);
        if (!empty($json)){
            $last_spider_price = $this->mem->get('last_spider_price');
            if ($last_spider_price != $json->ticker->last_rate){
                $last_spider_price =$json->ticker->last_rate;
                $this->mem->set('last_spider_price',$last_spider_price);
                $number = round(rand(10000,99999)/(rand(900,10000)),2);
                $this->new_ask_ticket('btc',$number,round($last_spider_price-round(rand(10000,99999)/(rand(900,10000)),2),0));
                $this->new_bid_ticket('btc',$number+rand(1,9)/10,round($last_spider_price+round(rand(10000,99999)/(rand(900,10000)),2),0));
                //$this->new_ask_ticket('btc',$number+rand(1,99)/10,$last_spider_price+rand(11,99)/10);
                //$this->new_bid_ticket('btc',$number+rand(1,99)/10,$last_spider_price-rand(11,99)/10);
                $this->start_match();
                $this->get_low_asks();
                $this->get_high_bids();
            }
        }
    }


    private  function start_match(){

        $asks = $this->get_available_asks();
        $bids = $this->get_available_bids();

        if ($asks == false || $bids == false){
            exit;
        }else{
            $sum = array('cny'=>0,'btc'=>0,'ltc'=>0);

            foreach($asks as &$ask_row){
                foreach($bids as &$bid_row){
                    //如果托管单状态为不可交易状态或买卖单为同一人，则不继续执行
                    if (($bid_row['status'] != 0)  || ($ask_row['user_id'] == $bid_row['user_id'])){
                        continue;
                    }
                    //因为bid结果已经过排序，所以如果价钱小于卖价或交易币种类型不相同，则不需要继续往后执行
                    if (($bid_row['price'] < $ask_row['price']) || ($ask_row['type'] != $bid_row['type'])){
                        break;
                    }

                    $trans_result = $this->transaction->new_transaction($bid_row,$ask_row);
                    if ($trans_result != false){
                        $sum[$ask_row['type']] += $trans_result['num'];
                        $sum['cny'] += $trans_result['num'] * $bid_row['price'];
                        $ask_row['available_num'] = $trans_result['ask_available'];
                        $ask_row['status'] = $trans_result['ask_status'];
                        $bid_row['available_num'] = $trans_result['bid_available'];
                        $bid_row['status'] = $trans_result['bid_status'];
                        if  ($trans_result['bid_status'] != 0){
                            unset($bid_row);
                        }
                        if ($trans_result['ask_status'] !=0){
                            break;
                        }
                    }

                }
            }
        }
    }




    private  function get_available_asks(){
        //select * from ask_ticket where status = 0 ORDER BY type,price asc
        $this->db->where('status',0);
        $this->db->where('user_id','10002');
        $this->db->order_by('type,price','ASC');
        $result =$this->db->get('ask_ticket');
        if ($result->num_rows() == 0){
            return false;
        }else{
            //更新最高价格
            $this->mem->set('highest_price',round($result->row($result->num_rows()-1)->price,2));
            $i = 0;
            foreach($result->result_array() as $row){
                $arr[]=$row;
            }
        }
        return $arr;
    }


    private  function get_available_bids(){
        //select * from bid_ticket where status = 0 ORDER BY type,price desc
        $this->db->where('status',0);
        $this->db->where('user_id','10003');
        $this->db->order_by('type,price','DESC');
        $result =$this->db->get('bid_ticket');
        if ($result->num_rows() == 0){
            return false;
        }else{
            //更新最低价格
            $this->mem->set('lowest_price',round($result->row($result->num_rows()-1)->price,2));
            $i=0;
            foreach($result->result_array() as $row){
                $arr[]=$row;
            }

        }
        return $arr;

    }

    /*
 * 生成新的卖方委托单
 * 并从用户钱包数据库里减去对应币种$number个金额
 */
    private  function new_ask_ticket($type,$number,$price){
        $this->db->select('count(*) as count_num',FALSE);
        $this->db->where(array('type'=>$type,
                'user_id'=>'10002',
                'status'=>'0')
        );
        $data = $this->db->get('ask_ticket');

        $this->db->trans_start();//执行严格事务，有一条错误则回滚
        //减去用户钱包中的金额
        $this->db->set($type,$type.'-'.$number,FALSE);
        $this->db->where('user_id','10002');
        $this->db->update('user_wallet');
        //生成卖方委托单
        $this->db->set(array(
                'user_id'=>'10002',
                'num'=>$number,
                'available_num'=>$number,
                'price'=>$price,
                'type'=>$type,
                'status'=>0,
                'time'=>date("Y-m-d H:i:s")
            )
        );
        $this->db->insert('ask_ticket');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            $this->_new_order_record($type,$price,$number,'ask');
            $this->log->log_wallet('10002',$type,'outcome',$number,'委托卖单：'.$this->db->insert_id());
            return TRUE;
        }
    }


    /*
     * 生成新的买方委托单
     * 同时减去用户钱包中对应人民币数量
     */
    private  function new_bid_ticket($type,$number,$price){
        $this->db->select('count(*) as count_num',FALSE);
        $this->db->where(array('type'=>$type,
                'user_id'=>'10003',
                'status'=>'0')
        );
        $data = $this->db->get('bid_ticket');

        $this->db->trans_start();//执行严格事务，有一条错误则回滚
        //减去用户钱包中的对应数量人民币
        $this->db->set('cny','cny-'.$number*$price,FALSE);
        $this->db->where('user_id','10003');
        $this->db->update('user_wallet');
        //生成买方委托单
        $this->db->set(array(
                'user_id'=>'10003',
                'num'=>$number,
                'available_num'=>$number,
                'price'=>$price,
                'type'=>$type,
                'status'=>0,
                'time'=>date("Y-m-d H:i:s")
            )
        );
        $this->db->insert('bid_ticket');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            $this->_new_order_record($type,$price,$number,'bid');
            $this->log->log_wallet('10003','cny','outcome',$number,'委托买单：'.$this->db->insert_id());
            return TRUE;

        }
    }

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


    private  function get_low_asks(){
        //select * from ask_ticket where status = 0 ORDER BY type,price asc
        $this->db->where('status',0);
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
    }


    private  function get_high_bids(){
        //select * from bid_ticket where status = 0 ORDER BY type,price desc
        $this->db->where('status',0);
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

    }
}