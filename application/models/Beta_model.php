<?php
class Beta_model extends CI_Model {
	//定义全局变量
	public $allsample;
    public $data_url;
    public $group_id;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->allsample = $this->config->item('allsample');
		$this->data_url = $this->config->item('data_url');
		$this->group_id = $this->session->userdata('mygroup');//print($group_id);die();
    }

}