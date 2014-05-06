 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Match_trade extends CI_Controller {
    public  function __construct(){
        parent::__construct();
        if (!$this->input->is_cli_request()){
            show_404();
            exit;
        }
        $this->load->model(array('transaction','log','mem'));
    }

    public  function start_match(){
        $start_time = date("Y-m-d H:i:s");
        //判断脚本运行标志如果为TRUE，则退出当前脚本
        if ($this->mem->get('is_running_script') == 'running'){
            $this->log->log_trade_match(-1,-1,-1,$start_time);
            exit();
        }
        $this->mem->set_by_time('is_running_script','running',0);
        $asks = $this->transaction->get_available_asks();
        $bids = $this->transaction->get_available_bids();
        if ($asks == false || $bids == false){
            $this->log->log_trade_match(0,0,0,$start_time);
            $this->mem->set_by_time('is_running_script','not_running',0);
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
            $this->mem->set_by_time('is_running_script','not_running',0);
            $this->log->log_trade_match($sum['cny'],$sum['btc'],$sum['ltc'],$start_time);

        }
    }
}