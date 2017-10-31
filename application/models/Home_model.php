<?php
class Home_model extends CI_Model {
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
    //获取该项目下的所有样品
    public function getallsample(){
		//读取项目下的otus.tb.tsv文件，获取全部样品信息
		$file_path = $this->data_url.$this->group_id."/".$this->allsample;
		$samplelist = '';
		if(file_exists($file_path)){
			$file = fopen($file_path,"r");
			$file_arr = fgets($file);
			$file_arr = trim(str_replace("\n","",$file_arr));
			$samplelist =explode("\t", $file_arr);
			array_splice($samplelist, 0, 1);
			array_pop($samplelist);
		}
		return $samplelist;
    }
    //获取所有分组列表
    public function getgrouplist(){
		//读取项目下的group文件夹，获取全部分组方案
		$file = scandir($this->data_url.$this->group_id."/group");
		$grouplist = '';
		foreach ($file as $key => $value) {
			if($value!='.' && $value!='..'){
				$grouplist['name'][] = $value;
			}
		}
		return $grouplist;
    }
    //根据groupname获取分组信息
    public function getgroupinfobyname($name){		
	    $dir = $this->data_url.$this->group_id."/group/".$name;
	    $groupinfo['groupname']=$name;
	    //获取分组方案的具体信息
		$file_path = $this->data_url.$this->group_id."/group/".$name.'/'.$name.'.conf';
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
				$groupinfo['fenzuname'][]=trim($str1);
				$str2 = substr($arr,$pos2+1);
				
				$str3 = explode(",",$str2);
				//要用foreach
				for($j=0;$j<count($str3);$j++){
					if(!empty($str3[$j])){
						$groupinfo['fenzucheck'][$info][] = trim($str3[$j]);
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
				$groupinfo['selectall'][$compare][] = trim($str2[0]);
				$groupinfo['selectall'][$compare][] = trim($str2[1]);	
				$compare++;				
			}
		}
		return $groupinfo;
    }
    //edit初始化group
    public function initialize_group($groupinfo,$allsample){
    	//初始化穿梭框
    	$samplelist = array();
    	$checksample = array();
    	foreach($groupinfo['fenzucheck'] as $k=>$arr){
    		$samplelist[$k] = $allsample;
    		$checksample[$k] = array();
    		foreach($arr as $value){
    			array_push($checksample[$k], $value);
    			$index = array_search($value, $allsample);
    			if ($index !== false){
				    array_splice($allsample, $index, 1);
				}
    		}
    	}
    	$allsample = [];//设置键值数组给穿梭框alldata
    	foreach ($samplelist as $k => $arr) {
    		foreach ($arr as $index => $val) {
				$allsample[$k][$index]['key'] = $val;
				$allsample[$k][$index]['value'] = $index;
			}
			sort($allsample[$k]);
    	}
		//设置group
		$group = array();
		for($i=0;$i<count($groupinfo['fenzuname']);$i++){
			$group[$i]['id'] = $i+1;
			$group[$i]['title'] = $groupinfo['fenzuname'][$i];
			$group[$i]['samplelist'] = $allsample[$i];
			$group[$i]['check'] = $checksample[$i];
		}
		return $group;
    }
    //edit初始化selectop
    public function initialize_selectop($groupinfo){
    	$selectop = array();
    	foreach($groupinfo['selectall'] as $index=>$arr){
    		//设置selectop
			foreach ($arr as $key => $value) {
				if($key==0){
					$value1 = $value;
				}else{
					$value2 = $value;
				}
			}
			$selectop[$index]['id']=$index+1;
			$selectop[$index]['checkone']=$value1;
			$selectop[$index]['checktwo']=$value2;
    	}
    	//print_r($selectop);die();
    	return $selectop;
    }
    //edit初始化options
    public function initialize_options($groupinfo){
    	//设置options
    	$options = array();
    	foreach($groupinfo['fenzuname'] as $key=>$value){
    		$options[$key]['label'] = $value;
    	}
    	return $options;
    }
    //新增一个otu
    public function otu_add($form,$groupname){
    	//print_r($form);die();
    	if(!file_exists($this->data_url.$this->group_id."/group/".$groupname."/".$form['name'])){
			mkdir($this->data_url.$this->group_id."/group/".$groupname."/".$form['name'],0777);
			//chmod($this->data_url.$this->group_id."/group/".$groupname."/".$form['name'],0777);				
		}
		//写入
		$myfile = fopen($this->data_url.$this->group_id."/group/".$groupname."/".$form['name']."/".$form['name'].".txt", "w") or die("Unable to open file!");
		fwrite($myfile, json_encode($form));
		fclose($myfile);
    }
    //判断是否有otu列表
	public function otu_exists($name){
		$otu = $this->home->getotulist($name);
		//print_r($name);print_r($otu);//die();
		if(empty($otu)){
			return 0;
		}else{
			return 1;
		}
	}
    //获取otu列表
    public function getotulist($groupname){
    	//读取groupname下的otu文件夹，获取全部otu列表
    	$result = '';
    	$filepath = $this->data_url.$this->group_id."/group/".$groupname;
    	//print($filepath);
    	if(file_exists($filepath)){
    		$file = scandir($filepath);
			foreach ($file as $key => $value) {
				if($value!='.' && $value!='..'){
					$otulist[] = $value;
				}
			}
			//print_r($otulist);
			if(!empty($otulist)){
				foreach($otulist as $key=>$value){
					//print_r($value);
					$dir = $this->data_url.$this->group_id."/group/".$groupname."/".$value;
					if(is_dir($dir)){
						$file = scandir($dir);
						foreach($file as $i=>$v){
							if($v!='.' && $v!='..'){
								//print($v);
								$filename = $dir."/".$value.".txt";
								$filename1 = $dir."/".$v;
								if(file_exists($filename) && $filename == $filename1){ 
								  $content = file_get_contents($filename); 
								  $json = json_decode('['.$content.']',true); 
								  $json[0]['datatable'] = 'ok';
								  
								  $filename2 = $dir."/".$value.".profile.xls";
								  // echo $filename2;
									if(file_exists($filename2)){ 
									  // echo $filename2;
									  $re = get_execl_content($filename2);
									  $json[0]['datatable'] = $re['datatable']?$re['datatable']:'';
									  $json[0]['columns'] = $re['columns'];
									}
									$result[] = $json[0];
								}
							}
						}
					}
				}
			}
    	}
    	//print_r($result);die();
    	return $result;
    }
	//读取groupname下的otu文件夹，获取otu$name
    public function getoneotu($name,$groupname){
		$filename = $this->data_url.$this->group_id."/group/".$groupname."/".$name."/".$name.'.txt';
		if(file_exists($filename)){ 
			$content = file_get_contents($filename); 
			$json = json_decode('['.$content.']',true); 
		}		
    	return $json[0];
    }

    public function otu_createtab($form,$groupname){
    	//生成otu json数据
    	$post_form = $form;
		unset($post_form['select1']);
		unset($post_form['state']);
		
		foreach($form as $index=>$arr){
			if($index=='species'){
				if($form['state']['speciesstate']=='false'){
					$post_form[$index] = '';
					$ifsave = $form['select1'];
					foreach($arr as $i=>$v){
						$str = '';
						foreach($v as $j=>$val){
							if($j!='id'){
								$str?$str=$str.','.$val:$str=$val;
							}
						}
						$post_form[$index][$ifsave][$i] = $str;
					}
				}else{
					unset($post_form['species']);
				}
				//print($form['state']['speciesstate']);die();
			}

			if($index=='depth'){
				if($form['state']['depthstate']=='false'){
					if($arr['class']=='true'){
						if($index=='depth'){
							if($arr['data']){
								$post_form[$index] = $arr['data'];
							}else{
								unset($post_form[$index]);
							}
						}else{
							$post_form[$index] = (float)$arr['data'];
						}
					}else{
						$post_form[$index] = (float)$arr['input'];
					}
				}else{
					unset($post_form['depth']);
				}
			}

			if($index=='absolute'){
				if($form['state']['absolutestate']=='false'){
					if($arr['class']=='true'){
						if($index=='depth'){
							if($arr['data']){
								$post_form[$index] = $arr['data'];
							}else{
								unset($post_form[$index]);
							}
						}else{
							$post_form[$index] = (float)$arr['data'];
						}
					}else{
						$post_form[$index] = (float)$arr['input'];
					}
				}else{
					unset($post_form['absolute']);
				}
			}

			if($index=='relative'){
				if($form['state']['relativestate']=='false'){
					if($arr['class']=='true'){
						if($index=='depth'){
							if($arr['data']){
								$post_form[$index] = $arr['data'];
							}else{
								unset($post_form[$index]);
							}
						}else{
							$post_form[$index] = (float)$arr['data'];
						}
					}else{
						$post_form[$index] = (float)$arr['input'];
					}
				}else{
					unset($post_form['relative']);
				}
			}

			if($index=='top'){
				if($form['state']['topstate']=='false'){
					if($arr['class']=='true'){
						if($index=='depth'){
							if($arr['data']){
								$post_form[$index] = $arr['data'];
							}else{
								unset($post_form[$index]);
							}
						}else{
							$post_form[$index] = (float)$arr['data'];
						}
					}else{
						$post_form[$index] = (float)$arr['input'];
					}
				}else{
					unset($post_form['top']);
				}
			}

			if($index=='relative'){
				if($arr['class']!='true'){
					$post_form[$index] = str_replace("%","",$arr['input']);
				}
			}
		}
		//var_dump($post_form);die();
		//读取分组方案信息
		$file_path = $this->data_url.$this->group_id."/group/".$groupname.'/'.$groupname.'.conf';
		if(file_exists($file_path)){
			$fp = fopen($file_path,"r");
			$file_arr = fgets($fp);
			$arr = explode("=",$file_arr);
			$post_form['samples'] = trim($arr[1]);
		}
		//读取项目下的otus.tb.tsv文件，获取全部样品信息
		$file_path = $this->data_url.$this->group_id."/".$this->allsample;
		if(file_exists($file_path)){
			$fp = fopen($file_path,"r");
			$post_form['otu'] = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
		}
		//写入
		$path = $this->data_url.$this->group_id."/group/".$groupname."/".$form['name']."/".$form['name'].".json";
		$myfile = fopen($path, "w") or die("Unable to open file!");
		fwrite($myfile, json_encode($post_form));
		fclose($myfile);
		//发送json数据给指定程序，执行生成文件
		$dir = $this->data_url.$this->group_id."/group/".$groupname."/".$form['name']."/";
		$cmd=escapeshellcmd('/state/partition1/workspace/software/R-3.2.5/bin/Rscript  /workspace/htdocs/16s-report/static/src/Otu_recast.R -j '.$path.' -o '.$dir);
		//print_r($cmd);//die();
		shell_exec($cmd);  
		unlink($path);

		$file = $this->data_url.$this->group_id."/group/".$groupname."/".$form['name']."/".$form['name'].".tab";
		if(file_exists($file)){
			return array("status"=>"1","data"=>"新增成功！","ref"=>"../index");
		}else{
			return array("status"=>"0","data"=>"程序执行失败！");
		}
    }
    //读取all.otus.tab，获取物种选择信息
    public function getspecieslist(){
    	$species=array("k__"=>"Domain","p__"=>"Phylum","c__"=>"Class","o__"=>"Order","f__"=>"Family","g__"=>"Genus","s__"=>"Species");
    	$species_arr=array("Domain"=>array(),"Phylum"=>array(),"Class"=>array(),"Order"=>array(),"Family"=>array(),"Genus"=>array(),"Species"=>array());
    	$filename = $this->data_url.$this->group_id."/all.otus.tab";
	  	$fp  = fopen ($filename, "r");
	  	
		while (!feof ($fp)){
		    $buffer  = fgets($fp);//取一行
		    $op = strstr(trim($buffer), 'Root;');//判断是否包含'Root;'
		    if($op){//包含就进行操作
		    	$string = substr($op,'5');//去除'Root;'
		    	$arr = explode(";",$string);//分割字符串
		    	foreach($arr as $key=>$value){
		    		if($value){
		    			foreach($species as $i=>$v){//遍历判断属于哪个物种
		    				if(strstr($value,$i)){
		    					if(!in_array($value,$species_arr[$v])){
		    						array_push($species_arr[$v],$value);
		    					} 
								
							}
		    			}
		    		}
		    	}
		    }
		}
		fclose ($fp);

		//print_r($species_arr);
		foreach ($species_arr as $key => $arr) {
			sort($arr);
			foreach ($arr as $index => $value) {
				$species_arr_new[$key][$index] = array("key"=>$index,"value"=>$value);
			}
		}
		//print_r($species_arr_new);die();
		return $species_arr_new;
	}
}