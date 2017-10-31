<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beta extends CI_Controller {

	public function __construct()
	{	
		parent::__construct();
		$this->load->model('Beta_model','beta');
		$this->load->model('Home_model','home');
		$this->load->model('Login_model','login');
		$this->load->helper('myfunction');//引用helper/myfunction_helper.php
	}

	public function  index(){
		$data['sidebar_title'] = 'beta';
		$this->load->view('public/header');
		$this->load->view('beta/index',$data);
		$this->load->view('public/footer');
	}

}