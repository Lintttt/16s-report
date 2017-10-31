<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alpha extends CI_Controller {

	public function __construct()
	{	
		parent::__construct();
		$this->load->model('Alpha_model','alpha');
		$this->load->model('Home_model','home');
		$this->load->model('Login_model','login');
		$this->load->helper('myfunction');//引用helper/myfunction_helper.php
	}

	public function index()
	{
		$this->login->check_login();//检查是否登录
		$group_id = $this->session->userdata('mygroup');
		$grouplist = $this->home->getgrouplist();

		if(!empty($_POST)){
			//print_r($_POST);die();
			$groupname = $_POST['groupname'];
			$otuname = $_POST['otuname'];
			$otulist = $this->home->getotulist($groupname);
			$otuselect = '';
			foreach($otulist as $key=>$value){
				$otuselect[] = $value['name'];
			} 
			//引用html路径
			$dir = 'data/'.$group_id."/group/".$groupname."/".$otuname."/alpha/alpha_plot/rarefaction_plots_new.html";
			$dir_boxplot = 'data/'.$group_id."/group/".$groupname."/".$otuname."/alpha/boxplot_new.html";
			if(!file_exists($dir) || !file_exists($dir_boxplot)){
				$this->alpha->create_alpha($groupname,$otuname,$dir,$dir_boxplot);
			}
			$img_url = '../data/'.$group_id."/group/".$groupname."/".$otuname."/alpha/alpha_plot/html_plots/";
			$boxplot_url = '../data/'.$group_id."/group/".$groupname."/".$otuname."/alpha";
			$alpha_columndata = $this->alpha->alpha_columndata($groupname,$otuname);

			$data2['alpha_columndata'] = $alpha_columndata;
			$data2['src'] = $dir;
			$data2['src_boxplot'] = $dir_boxplot;
			$data2['img_url'] = $img_url;
			$data2['boxplot_url'] = $boxplot_url;
			$data['groupname'] = $groupname;
			$data['otuname'] = $otuname;
			$data['otulist'] = array(""=>"")+$otuselect;
			$data['grouplist'] = array(""=>"")+$grouplist['name'];
			$data['sidebar_title'] = 'alpha';
			$this->load->view('public/header');
			$this->load->view('alpha/index',$data);
			$this->load->view('alpha/draw',$data2);
			$this->load->view('alpha/alpha_foot');
			$this->load->view('public/footer');
		}else{
			$otuselect = '';$otuselect2 = '';
			if(!empty($grouplist)){
				$grouplist2 = array(""=>"")+$grouplist['name'];
				$otulist = $this->home->getotulist($grouplist['name'][0]);
				
				if(!empty($otulist)){
					foreach($otulist as $key=>$value){
						$otuselect[] = $value['name'];
					}
					$otuselect2 = array(""=>"")+$otuselect;
				}else{
					$otuselect2 = '';
				}
				
			}else{
				$grouplist2 = '';
			}
			
			$data['otulist'] = $otuselect2;
			$data['grouplist'] = $grouplist2;
			$data['sidebar_title'] = 'alpha';
			$this->load->view('public/header');
			$this->load->view('alpha/index',$data);
			$this->load->view('alpha/alpha_foot');
			$this->load->view('public/footer');
		}
	}

	public function draw()
	{
		$this->login->check_login();//检查是否登录
		$data['sidebar_title'] = 'alpha';

		$this->load->view('public/header');
		//$this->load->view('alpha/index');
		$this->load->view('alpha/draw',$data);
		$this->load->view('public/footer');
	}

	public function ajax_getotulist(){
		$name = $_POST['name'];
		$otulist = $this->home->getotulist($name);
		if(empty($otulist)){
			echo json_encode(array("status"=>"0","data"=>"该分组还没有otu丰度表,请先创建！"));
		}else{
			$otuselect = '';
			foreach($otulist as $key=>$value){
				$otuselect[] = $value['name'];
			}
			echo json_encode(array("status"=>"1","data"=>$otuselect));
		}
	}

	public function alpha_post(){
		$this->login->check_login();//检查是否登录
		$groupname = $_POST['groupname'];
		$otuname = $_POST['otuname'];

		$data_url = $this->config->item('data_url');
		$group_id = $this->session->userdata('mygroup');

		$group_url = $data_url.$group_id."/group/".$groupname."/".$groupname.".txt";
		$otu_url = $data_url.$group_id."/group/".$groupname."/".$otuname."/".$otuname.".tab";
		$dir = $data_url.$group_id."/group/".$groupname."/".$otuname."/alpha/";
		// print_r($group_url);
		// print_r($otu_url);
		// $cmd=escapeshellcmd('perl /workspace/htdocs/16s-report/static/src/alpha.pl '.$otu_url.' '.$group_url.' '.$dir);
		// var_dump($cmd);
		// shell_exec($cmd);
		//die();
		$src = 'data/'.$group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot/html_plots/rarefaction_plots.html';
		//print($path);
		//print($src);
		if(file_exists($src)){
			$data2['src'] = $src;;
			$this->load->view('alpha/draw',$data2);
			//echo json_encode(array("status"=>"1","src"=>$src));
		}else{
			// echo json_encode(array("status"=>"0","message"=>"<strong>警告!</strong>找不到相应的文件。"));
			$data2['src'] = $src;;
			$this->load->view('alpha/draw',$data2);
			//echo json_encode(array("status"=>"1","src"=>$src));
		}
		//redirect('alpha/draw');
	}
}