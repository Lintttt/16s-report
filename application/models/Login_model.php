<?php
class Login_model extends CI_Model {
	public function __construct()
    {
        parent::__construct();
    }

    public function check_login(){		
		if(!$this->session->myname || !$this->session->mypassword){
			redirect('login/index');
		}
	}

}