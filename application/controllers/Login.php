<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 登录
*/
class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		if(!empty($_POST)){
			if($_POST['username']=='myciadmin'&&$_POST['password']=='123456'){
				$this->session->set_userdata('mygroup', 'GDD0000_16S');
				$this->session->set_userdata('myname', $_POST['username']);
				$this->session->set_userdata('mypassword', $_POST['password']);
				redirect('home/index');
			}else{
				echo "<script>alert('username or password is wrong！');</script>";
			}
		}

		$this->load->view('public/header');
		$this->load->view('Login/index');
		$this->load->view('public/footer');
	}
}


