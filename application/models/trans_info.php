<?php
class Trans_info extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->model('mem');
    }

    /**
     * 获取系统中最佳出价
     */
    public function get_best_bid_price(){
        $price = $this->mem->get('best_bid_price');
        if (empty($price)){
            $this->db->select_max('price');
            $this->db->where('status',0);
            $data = $this->db->get('bid_ticket');
        }else{
            return $price;
        }
    }


    /*
     * 获取24小时内的所有5分钟K线图数据
     * 并写入缓存
     */
    public function get_5min_kline_data($time){
        $this->db->where('k_time>=',$time,false);
        $data =$this->db->get('kline_min');
        $return_data = array();
        if ($data->num_rows()>0){
            foreach ($data->result() as $row){
               $timestamp = strtotime($row->k_time).'000';
               $return_data[] = array($timestamp,
                                      round($row->open,2),
                                      round($row->high,2),
                                      round($row->low,2),
                                      round($row->close,2),
                                      round($row->num,4)
               );
            }
            $data = (json_encode($return_data));
            $data =  str_replace('"','',$data);
            $this->mem->set_by_time('kline_min',$data,30);
            return $data;
        }
    }

    /*
     * 获取日K线图数据
     * 并写入缓存
     */
    public function get_day_kline_data(){
        $this->db->order_by('k_time','ASC');
        $data = $this->db->get('kline_day');
        $return_data = array();
        if ($data->num_rows()>0){
            foreach ($data->result() as $row){
                $timestamp = strtotime($row->k_time).'000';
                $return_data[] = array($timestamp,
                    round($row->open,2),
                    round($row->high,2),
                    round($row->low,2),
                    round($row->close,2),
                    round($row->num,4)
                );
            }
            $data = (json_encode($return_data));
            $data =  str_replace('"','',$data);
            $this->mem->set_by_time('kline_day',$data,60);
            return $data;
        }
    }


    /*
     * 返回市场深度的HTML
     */
    /*
 * 输出列表HTML
 */
    public function depth_html(){
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
        $html='';
        for ($i=0;$i<10;$i++){
            if ($i>4){
                $num = abs(4-$i);
                $html .='<tr><td>'.round($data[$i]['num'],4).
                    '</td><td class="center">￥'
                    .round($data[$i]['price'],2).'</td>
                                    <td class="center">
                                        <span class="label label-important">卖'. $num .'</span></td></tr>';
            }else{
                $num = abs($i-4)+1;
                $html .='<tr><td>'.round($data[$i]['num'],4).
                    '</td><td class="center">￥'
                    .round($data[$i]['price'],2).'</td>
                                    <td class="center">
                                        <span class="label label-success">买'.$num.'</span>
                                    </td></tr>';
            }
        }
        return ($html);
    }

}