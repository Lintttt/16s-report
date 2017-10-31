<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 首页
*/
class HighChart extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index(){
		$data['sidebar_title'] = 'highchart';
		$this->load->view('public/header');
		$this->load->view('highchart/index',$data);
		$this->load->view('public/footer');
	}


}
