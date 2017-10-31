<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 首页
*/
class Home extends CI_Controller
{
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model('Home_model','home');
		$this->load->model('Login_model','login');
		$this->load->helper('myfunction');//引用helper/myfunction_helper.php
	}

	public function index(){
		$this->login->check_login();//检查是否登录
		$samplelist = $this->home->getallsample();//获取所有样品
		$grouplist = $this->home->getgrouplist();//获取所有分组
		//print_r($string_name);die();
		$data['group'] = $grouplist;
		$data['group_list'] = $samplelist;
		$data['sidebar_title'] = 'home';
		
		$this->load->view('public/header');
		$this->load->view('home/index',$data);
		$this->load->view('public/footer');
	}

	public function grouping(){//根据表单信息，创建，编辑分组文件夹和写文件
		$data_url = $this->config->item('data_url');
		if(!empty($_POST)){
			//print_r($_POST);die();
			$type = $_POST['type'];
			if($type == 'edit'){//编辑,先删除后生成
				$group_name = $this->session->userdata('mygroup');
				$name = $_POST['oldname'];
			    $dir = $data_url.$group_name."/group/".$name;
			    
				$result = deldir($dir);
				if(!$result){
					echo json_encode(array('status'=>'0','data'=>'删除失败！')); 
				}			
			}			
			//新建文件夹
			$name = $_POST['groupname'];
			$group_id = $this->session->userdata('mygroup');
			if(!file_exists($data_url.$group_id."/group/".$name)){
				mkdir($data_url.$group_id."/group/".$name);
				chmod($data_url.$group_id."/group/".$name,0777);				
			}
			//写txt文件
			$arr = '';
			foreach($_POST['fenzuname'] as $i=>$v){
				foreach($_POST['fenzucheck'][$i] as $key=>$value){
					$arr[$v][$key] = $value;
				}
				sort($arr[$v]);
			}
			//print_r($arr);die();
			$groupxtxt = "#SampleID\tGroup\n";
			foreach ($arr as $i => $val) {
				foreach ($val as $key => $value) {
					$groupxtxt=$groupxtxt.$value."\t".$i."\n";
				}
			}
			//print($groupxtxt);die();
			$myfile = fopen($data_url.$group_id."/group/".$name."/".$name.".txt", "w") or die("Unable to open file!");
			fwrite($myfile, $groupxtxt);
			fclose($myfile);
			//写conf文件
			$allsample = '';$group = '';
			foreach ($_POST['fenzucheck'] as $i=>$arr) {
				$group?($group=$group.($i+1)." = ".$_POST['fenzuname'][$i].":"):($group=($i+1)." = ".$_POST['fenzuname'][$i].":");
				foreach ($arr as $j=>$value) {
					$j==0?$group=$group.$value:$group=$group.','.$value;
					$allsample?($allsample=$allsample.','.$value):($allsample=$value);
				}
				$group=$group."\n";
			}
			$text1 = "samples = ".$allsample."\n";
			$text2 = "<groups>\n".$group."</groups>\n";
			$select = '';
			foreach ($_POST['selectall'] as $arr) {
				foreach($arr as $key => $value){
					if($key == 0){
						$value1=$value;
					}
					if($key == 1){
						$value2=$value;
					}
				}
				$select?$select=$select.','.$value1.'&'.$value2:$select=$select.$value1.'&'.$value2;
			}
			$text3 = 'two_groups_diff   = '.$select."\nmulti_groups_diff =\nlefse_groups_diff =\n";
			//print($text1);print($text2);print($text3);die();
			$text4 = "project_id = ".$name."\nrawdata = none\n<<include etc/database.conf>>\n<<include etc/software.conf>>";
			$myfile = fopen($data_url.$group_id."/group/".$name."/".$name.".conf", "w") or die("Unable to open file!");
			$txt = $text1.$text2.$text3.$text4;
			fwrite($myfile, $txt);
			fclose($myfile);
			if($type == 'edit'){
				$data = '编辑成功！';
				$ref = '../index';
			}elseif($type == 'create'){
				$data = '新增成功！';
				$ref = 'index';
			}else{
				$data = '另存为成功！';
				$ref = '../index';
			}
			echo json_encode(array('status'=>'1','data'=>$data ,'ref'=>$ref)); 	
		}
	}

	public function ajax_grouping_delete(){//ajax删除文件操作
		$data_url = $this->config->item('data_url');
		$group_name = $this->session->userdata('mygroup');

		$name = $_POST['name'];
	    $dir = $data_url.$group_name."/group/".$name;
		if(deldir($dir)){
			echo json_encode(array('status'=>'1','data'=>'删除成功！')); 
		}else{
			echo json_encode(array('status'=>'0','data'=>'删除失败！')); 
		}
	}


	public function ajax_grouping_search(){//ajax查看对应的分组
		$name = $_POST['name'];
		$result = $this->getinfo($name);
		if(!empty($result)){
			echo json_encode(array('status'=>'1','data'=>$result)); 
		}else{
			echo json_encode(array('status'=>'0','data'=>'该分组方案查找失败!')); 
		}
	}

	public function getinfo($name){//获取对应group的信息
		$data_url = $this->config->item('data_url');
		$group_name = $this->session->userdata('mygroup');
	    $dir = $data_url.$group_name."/group/".$name;
	    $groupinfo['name']=$name;
	    //获取分组方案的具体信息
		$file_path = $data_url.$group_name."/group/".$name.'/'.$name.'.conf';
		if(file_exists($file_path)){
			//获取对应conf文件的信息到一个字符串进行处理
			$qian=array(" ","　","\t","","\r");
			$hou=array("","","","","");
			$file_arr = trim(str_replace($qian,$hou,file_get_contents($file_path)));
			//<groups></groups>之间的分组
			$pos1 = strpos($file_arr, '<groups>');
			$pos2 = strpos($file_arr, '</groups>');
			$str = trim(substr($file_arr,$pos1+8,$pos2-$pos1-8));
				
			$str1 = explode("\n",$str);
			$info = 0;
			foreach($str1 as $arr){
				$pos1 = strpos($arr, '=');
				$pos2 = strpos($arr, ':');
				$str1 = substr($arr,$pos1+1,$pos2-$pos1-1);
				$groupinfo['group'][]=trim($str1);
				$str2 = substr($arr,$pos2+1);
				
				$str3 = explode(",",$str2);
				//要用foreach
				for($j=0;$j<count($str3);$j++){
					if(!empty($str3[$j])){
						$groupinfo['info'][$info][] = trim($str3[$j]);
					}
				}
				$info++;				
			}
			//two_groups_diff，multi_groups_diff之间的组间比较
			$pos1 = strpos($file_arr, 'two_groups_diff');
			$pos2 = strpos($file_arr, 'multi_groups_diff');
			$str = substr($file_arr,$pos1+16,$pos2-$pos1-16);
			$str1 = explode(",",$str);
			$compare = 0;
			foreach($str1 as $arr){
				$str2 = explode("&",$arr);
				$groupinfo['compare'][$compare][] = trim($str2[0]);
				$groupinfo['compare'][$compare][] = trim($str2[1]);	
				$compare++;				
			}
			return $groupinfo;
		}else{
			return '';
		}
	}

	public function ajax_grouping_checklist(){//ajax获取checklist
		$allsample = $this->config->item('allsample');
		$data_url = $this->config->item('data_url');
		$check_list = $_POST['checklist'];//print_r($check_list);
		$index = $_POST['index'];

		$group_id = $this->session->userdata('mygroup');//print($group_id);die();
		//读取项目下的otus.tb.tsv文件，获取全部样品信息
		$file_path = $data_url.$group_id."/".$allsample;
		
		if(file_exists($file_path)){
			$file = fopen($file_path,"r");
			$file_arr = fgets($file);
			$file_arr = trim(str_replace("\n","",$file_arr));
			$group_list =explode("\t", $file_arr);
			array_splice($group_list, 0, 1);
		}
		$str="<td class='edit_checktd'>";
		foreach($group_list as $key=>$value){
			$str=$str."<label class='edit".$value."'><input type='checkbox' value='".$value."' name='check[".$index."][]'>".$value."</label>"; 
		}
		$str=$str."</td><td></td>";
		//echo $str;
		echo json_encode($str);
	}

	public function grouping_edit(){//根据表单信息，修改信息
		$data_url = $this->config->item('data_url');
		if(!empty($_POST)){
			//print_r($_POST);die();
			$name = $_POST['edit_name'];
			$group_id = $this->session->userdata('mygroup');
		
				$allsample = '';$group = '';
				foreach ($_POST['check'] as $i=>$arr) {
					$group?($group=$group.$i." = ".$_POST['group'][$i].":"):($group=$i." = ".$_POST['group'][$i].":");
					foreach ($arr as $j=>$value) {
						$j==0?$group=$group.$value:$group=$group.','.$value;
						$allsample?($allsample=$allsample.','.$value):($allsample=$value);
					}
					$group=$group."\n";
				}
				$text1 = "samples = ".$allsample."\n";
				$text2 = "<groups>\n".$group."</groups>\n";

				$select = '';
				foreach ($_POST['select'] as $key => $value) {
					if($key%2==0){
						$value1=$_POST['select'][$key+1];
						$select?$select=$select.','.$value.'&'.$value1:$select=$select.$value.'&'.$value1;
					}
				}
				$text3 = 'two_groups_diff   = '.$select."\nmulti_groups_diff =\nlefse_groups_diff =\n";
				//print($text1);print($text2);print($text3);die();

				$myfile = fopen($data_url.$group_id."/group/".$name."/".$name.".conf", "w") or die("Unable to open file!");
				$txt = $text1.$text2.$text3;
				fwrite($myfile, $txt);
				fclose($myfile);
				redirect('home/index');
		}
	}

	public function ajax_changename(){//ajax改变文件夹名
		$data_url = $this->config->item('data_url');
		if(!empty($_POST)){
			//print_r($_POST);die();
			$old = $_POST['old'];
			$now = $_POST['now'];
			$group_id = $this->session->userdata('mygroup');
			if(file_exists($data_url.$group_id."/group/".$now)){
				$result = array('status'=>0,'data'=>'存在相同名称的分组方案，修改失败!'); 
			}else{
				if(file_exists($data_url.$group_id."/group/".$old)&&file_exists($data_url.$group_id."/group/".$old.'/'.$old.'.conf')){
					if(rename($data_url.$group_id."/group/".$old."/".$old.".conf", $data_url.$group_id."/group/".$old."/".$now.".conf")){
						if(rename($data_url.$group_id."/group/".$old, $data_url.$group_id."/group/".$now)){
							$result = array('status'=>1,'data'=>'修改成功!');
						}else{
							$result = array('status'=>0,'data'=>'修改失败!'); 
						}
					}
					else{
						$result = array('status'=>0,'data'=>'修改失败!'); 
					}
				}else{
					$result = array('status'=>0,'data'=>'没有找到该分组方案，修改失败!'); 
				}
			}
			echo json_encode($result);
		}
	}

	public function ajax_checkname(){
		if(!empty($_POST)){
			$data_url = $this->config->item('data_url');
			$name = $_POST['name'];
			$group_id = $this->session->userdata('mygroup');
			//读取项目下的group文件夹，获取全部分组方案
			$file = scandir($data_url.$group_id."/group");
			$flag = 1;
			foreach ($file as $key => $value) {
				if($name == $value){
					$flag = 0;
				}
			}
			
			if($flag){
				echo json_encode(array('status'=>1));
			}else{
				echo json_encode(array('status'=>0,'data'=>'该分组名称已存在，请重新填写！'));
			}
		}
	}

	public function create(){
		$this->login->check_login();//检查是否登录
		$samplelist = $this->home->getallsample();//获取所有样品
		
		$data = [];//设置键值数组给穿梭框alldata
		foreach ($samplelist as $index => $val) {
			$data[$index]['key'] = $val;
			$data[$index]['value'] = $index;
		}
		sort($data);
		//print_r($data);die();
		//$data['group'] = $namelist;
		$data['data'] = $data;
		$data['sidebar_title'] = 'home';
		
		$this->load->view('public/header');
		$this->load->view('home/create',$data);
		$this->load->view('public/footer');
	}

	public function edit($name){ 
		$this->login->check_login();//检查是否登录
		if(!empty($_POST)){
			print_r($_POST);die();
		}
		$groupinfo = $this->home->getgroupinfobyname($name);//根据groupname获取分组信息
		$samplelist = $this->home->getallsample();//获取所有样品

		$allsample = [];//设置键值数组给穿梭框alldata
		foreach ($samplelist as $index => $val) {
			$allsample[$index]['key'] = $val;
			$allsample[$index]['value'] = $index;
		}
		sort($allsample);
		//print_r($groupinfo);
		$group = $this->home->initialize_group($groupinfo,$samplelist);//初始化值group
		$selectop = $this->home->initialize_selectop($groupinfo);//初始化值selectop
		$options = $this->home->initialize_options($groupinfo);//初始化值options
		
		$data['group'] = $group;
		$data['selectop'] = $selectop;
		$data['options'] = $options;
		$data['allsample'] = $samplelist;
		$data['data'] = $groupinfo;
		$data['sidebar_title'] = 'home';
		$data['name'] = $name;
		//print_r($data);//die();
		$this->load->view('public/header');
		$this->load->view('home/edit',$data);
		$this->load->view('public/footer');
	}
	//otu_add
	public function otu_add($name){
		$this->login->check_login();//检查是否登录
		
		$data['name'] = $name;
		$data['select1'] = $this->config->item('ifsave');
		$data['select2'] = $this->config->item('species_select');
		$data['selectlist'] = $this->home->getspecieslist();
		$data['sidebar_title'] = 'home';
		$this->load->view('public/header');
		$this->load->view('home/otu_add',$data);
		$this->load->view('public/footer');
	}
	//ajax_get_otu_add
	public function ajax_otu_add(){
		if(!empty($_POST)){
			//print_r($_POST);die();
			$form = $_POST['form'];
			$groupname = $_POST['groupname'];
			$this->home->otu_add($form,$groupname);//新增otu
			$result = $this->home->otu_createtab($form,$groupname);//新增otu后生成tab等相关文件
			echo json_encode($result);
		}
	}
	//groupname对应的otu列表
	public function otu_edit($name){
		$this->login->check_login();//检查是否登录
		
		$otu = $this->home->getotulist($name);
		$data['otu'] = $otu;
		$data['name'] = $name;
		$data['select1'] = $this->config->item('ifsave');
		$data['select2'] = $this->config->item('species_select');
		$data['selectlist'] = $this->home->getspecieslist();
		$data['sidebar_title'] = 'home';
		$this->load->view('public/header');
		$this->load->view('home/otu_edit',$data);
		$this->load->view('public/footer');
	}
	//ajax保存单个otu
	public function ajaxsaveotu(){
		if(!empty($_POST)){
			$form = $_POST['otu'];
			$groupname = $_POST['groupname'];
			//先删除在进行新增
			$data_url = $this->config->item('data_url');
			$group_id = $this->session->userdata('mygroup');
			$dir = $data_url.$group_id."/group/".$groupname."/".$form['name'];
			if(deldir($dir)){
				$this->home->otu_add($form,$groupname);//新增otu
				$result = $this->home->otu_createtab($form,$groupname);//新增otu后生成tab等相关文件
				$result['data'] = '修改成功!';
				echo json_encode($result);
			}else{
				echo json_encode(array("status"=>"0","data"=>"修改失败！"));
			}
		}
	}
	//ajax删除单个otu
	public function ajaxdelotu(){
		if(!empty($_POST)){
			$name = $_POST['name'];
			$groupname = $_POST['groupname'];
			$data_url = $this->config->item('data_url');
			$group_id = $this->session->userdata('mygroup');
			$dir = $data_url.$group_id."/group/".$groupname."/".$name;
			if(deldir($dir)){
				echo json_encode(array("status"=>"1","data"=>"删除成功！"));
			}else{
				echo json_encode(array("status"=>"0","data"=>"删除失败！"));
			}
		}
	}
	//ajax检查otu名称
	public function ajax_otu_checkname(){
		if(!empty($_POST)){
			$name = $_POST['name'];
			$groupname = $_POST['groupname'];
			$data_url = $this->config->item('data_url');
			$group_id = $this->session->userdata('mygroup');
			$filename = $data_url.$group_id."/group/".$groupname."/".$name."/".$name.'.txt';
			if(file_exists($filename)){ 
				echo json_encode(array("status"=>"1","data"=>"该名称已存在，请重新填写！"));
			}else{
				echo json_encode(array("status"=>"0"));
			}
		}
	}
}
