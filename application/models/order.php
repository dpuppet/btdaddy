<?php
class Order extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('');
    }

    /*
     * 输出用户购买记录的HTML格式
     */
    public function get_bid_list_html(){
        $raw_data = $this->get_bid_list_raw();
        $html = '';
        if ($raw_data == NULL){

        }else{
            foreach($raw_data as $row){
            switch($row['status']){
                case 0:
                    $status = '<span class="label label-warning">交易中</span>';
                    break;
                case 1:
                    $status = '<span class="label label-success">已完成</span>';
                    break;
                case 2:
                    $status = '<span class="label">已取消</span>';
                    break;
            }
            $html .=<<<HTML
                            <tr><td>$row[time]</td>
                                <td class="center">$row[price]</td>
                                <td class="center">$row[num]</td>
                                <td class="center">$row[deal_num]</td>
                                <td class="center">
                                    $status
                                </td></tr>
HTML;

            }
        }
        return $html;
    }

    /*
     * 输出用户卖出记录的HTML格式
     */
    public function get_ask_list_html(){
        $raw_data = $this->get_ask_list_raw();
        $html = '';
        if ($raw_data == NULL){

        }else{
            foreach($raw_data as $row){
                switch($row['status']){
                    case 0:
                        $status = '<span class="label label-warning">交易中</span>';
                        break;
                    case 1:
                        $status = '<span class="label label-success">已完成</span>';
                        break;
                    case 2:
                        $status = '<span class="label">已取消</span>';
                        break;
                }
                $html .=<<<HTML
                            <tr><td>$row[time]</td>
                                <td class="center">$row[price]</td>
                                <td class="center">$row[num]</td>
                                <td class="center">$row[deal_num]</td>
                                <td class="center">
                                    $status
                                </td></tr>
HTML;

            }
        }
        return $html;
    }


    /*
     * 获取用户的购买记录
     * 时间，价格，委托数量，已完成数量，状态：[完结，取消，正常]
     */
    public function get_bid_list_raw(){
        $this->db->where('user_id',$_SESSION['uid']);
        $this->db->limit(10);
        $this->db->order_by('time','DESC');
        $data = $this->db->get('bid_ticket');
        if ($data->num_rows()>0){
            $data = $data->result_array();
            foreach($data as &$row){
                $row['deal_num'] = round($row['num'] - $row['available_num'],4);
                $row['price'] = round($row['price'],2);
                $row['num'] = round($row['num'],4);

                //unset($row['id']);
                unset($row['available_num']);
                unset($row['user_id']);
            }
            return $data;
        }else{
            return NULL;
        }
    }

    /*
     * 获取用户的卖出记录
     * 时间，价格，委托数量，已完成数量，状态：[完结，取消，正常]
     */
    public function get_ask_list_raw(){
        $this->db->where('user_id',$_SESSION['uid']);
        $this->db->limit(10);
        $this->db->order_by('time','DESC');
        $data = $this->db->get('ask_ticket');
        if ($data->num_rows()>0){
            $data = $data->result_array();
            foreach($data as &$row){
                $row['deal_num'] = round($row['num'] - $row['available_num'],4);
                $row['price'] = round($row['price'],2);
                $row['num'] = round($row['num'],4);

                //unset($row['id']);
                unset($row['available_num']);
                unset($row['user_id']);
            }
            return $data;
        }else{
            return NULL;
        }
    }


    /*
     * 输出管理面板中用户购买记录的HTML格式
     */
    public function get_bid_list_html_for_manage(){
        $raw_data = $this->get_bid_list_raw();
        $html = '';
        if ($raw_data == NULL){

        }else{
            foreach($raw_data as $row){
                $op ='';
                switch($row['status']){
                    case 0:
                        $status = '<span class="label label-warning">交易中</span>';
                        $op = '<button class="btn btn-small btn-danger" data-type="bid" data-id="'.$row['id'].'">取消交易</button>';
                        break;
                    case 1:
                        $status = '<span class="label label-success">已完成</span>';
                        break;
                    case 2:
                        $status = '<span class="label">已取消</span>';
                        break;
                }
                $html .=<<<HTML
                            <tr><td>$row[time]</td>
                                <td class="center">$row[price]</td>
                                <td class="center">$row[num]</td>
                                <td class="center">$row[deal_num]</td>
                                <td class="center">
                                    $status
                                </td>
                                <td class="center">$op</td></tr>
HTML;

            }
        }
        return $html;
    }

    /*
     * 输出管理面板中用户卖出记录的HTML格式
     */
    public function get_ask_list_html_for_manage(){
        $raw_data = $this->get_ask_list_raw();
        $html = '';
        if ($raw_data == NULL){

        }else{
            foreach($raw_data as $row){
                $op ='';
                switch($row['status']){
                    case 0:
                        $status = '<span class="label label-warning">交易中</span>';
                        $op = '<button class="btn btn-small btn-danger" data-type="ask" data-id="'.$row['id'].'">取消交易</button>';
                        break;
                    case 1:
                        $status = '<span class="label label-success">已完成</span>';
                        break;
                    case 2:
                        $status = '<span class="label">已取消</span>';
                        break;
                }
                $html .=<<<HTML
                            <tr><td>$row[time]</td>
                                <td class="center">$row[price]</td>
                                <td class="center">$row[num]</td>
                                <td class="center">$row[deal_num]</td>
                                <td class="center">
                                    $status
                                </td>
                                <td class="center">$op</td></tr>
HTML;

            }
        }
        return $html;
    }


    /**
     * 获取用户挂单数量信息
     */
    public function get_transaction_num($status){
        $this->db->where('user_id',$_SESSION['uid']);
        $this->db->where('status',$status);
        $this->db->select('count(*) as num',false);
        $ask_num = $this->db->get('ask_ticket')->row()->num;

        $this->db->where('user_id',$_SESSION['uid']);
        $this->db->where('status',$status);
        $this->db->select('count(*) as num',false);
        $bid_num = $this->db->get('bid_ticket')->row()->num;

        return ($ask_num+$bid_num);
    }

}