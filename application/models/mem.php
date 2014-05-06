<?php
class Mem extends CI_Model {

    public $mem;
    public $host,$port;

    public function __construct(){
        parent::__construct();
        $host = $this->config->item('memcache_host');
        $port = $this->config->item('memcache_port');
        $this->mem = new Memcache;
        $this->mem->addServer($host,$port);
    }


    public function get($name){
        $data=$this->mem->get($name);
        return $data;
    }

    public function set($name,$str){
        $data=($this->mem->set($name,$str,0));
        return $data;
    }

    public function set_by_time($name, $str, $expire)
    {
        $data = $this->mem->set($name, $str, 0, $expire);
        return $data;
    }

    public function delete($name, $expire=0)
    {
        return $this->mem->delete($name, $expire);
    }

    public function flush(){
        return $this->mem->flush();
    }
    public function __destruct(){
        $this->mem->close();
    }

}