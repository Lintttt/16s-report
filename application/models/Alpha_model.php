<?php
class Alpha_model extends CI_Model {
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

    public function create_alpha($groupname,$otuname,$dir1,$dir2){
    	if(!file_exists($dir1)){
    		$group_url = $this->data_url.$this->group_id."/group/".$groupname."/".$groupname.".txt";
	    	$conf_url = $this->data_url.$this->group_id."/group/".$groupname."/".$groupname.".conf";
			$otu_url = $this->data_url.$this->group_id."/group/".$groupname."/".$otuname."/".$otuname.".tab";
			$dir = $this->data_url.$this->group_id."/group/".$groupname."/".$otuname."/alpha/";
			// print_r($group_url);
			// print_r($otu_url);
			//生成alpha_plot等文件
			$cmd='source /etc/profile;perl /state/partition1/workspace/pipe/meta16S/extras/alpha.pl '.escapeshellarg($otu_url).' '.escapeshellarg($group_url).' '.escapeshellarg($conf_url).' '.escapeshellarg($dir) ."  2>&1 ";
			// var_dump($cmd);
			$result = shell_exec($cmd);
			//$result = shell_exec($cmd);
			$dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot/rarefaction_plots.html';
			//print($dir);
			if(file_exists($dir)){
				$this->change_imgurl($dir,$groupname,$otuname);
			}else{
				var_dump($cmd);
				echo $result."\n程序执行出错！";
				die();
			}
    	}
    	if(!file_exists($dir2)){
			//生成boxplot等文件,用来生成箱线图
			$summary_url = $this->data_url.$this->group_id."/group/".$groupname."/".$otuname."/alpha/summary.alpha_diversity.xls";
	    	$allqiimegroups_url = $this->data_url.$this->group_id."/all.qiime.groups";
			$dir = $this->data_url.$this->group_id."/group/".$groupname."/".$otuname."/alpha/boxplot.html";
			$cmd='source /etc/profile;/state/partition1/workspace/software/R-3.3.3/lib64/R/bin/Rscript /workspace/htdocs/16s-report/static/src/plotly_box.R '.$summary_url.' '.$allqiimegroups_url.' '.$dir."  2>&1 ";
			// var_dump($cmd);
			$result = shell_exec($cmd);
			if(file_exists($dir)){
				$this->change_boxplot($dir,$groupname,$otuname);
			}else{
				var_dump($cmd);
				echo $result."\n程序执行出错！";
				die();
			}
		}
    }

    public function change_imgurl($dir,$groupname,$otuname){
    	//给alpha_plot写入权限
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/',0777);
    	//alpha_plot
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot',0777);

    	$new_dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot/rarefaction_plots_new.html';
    	$file = fopen($dir, "r");
		//输出文本中所有的行，直到文件结束为止。
		$newfile = fopen($new_dir, "w") or die("Unable to open file!");
		//写入新的文件
		while(!feof($file)){
			$content= fgets($file);
	    	if(strstr($content,"\"./html_plots/\"")){
	    		$str = str_replace("\"./html_plots/\"","\"<?php echo \$img_url?>\"",$content);
				fwrite($newfile, $str);
	    	}else if(strstr($content,"img.setAttribute('id',array[i]+'_ave'+imagetype)")){
	    		$str = str_replace("img.setAttribute('id',array[i]+'_ave'+imagetype)","img.setAttribute('id',SelObject.value+array[i]+'_ave'+imagetype)",$content);
				fwrite($newfile, $str);
	    	}else{
	    		fwrite($newfile, $content);
	    	}
	    }
	    fclose($file);
	    fclose($newfile);

	    //alpha_plot
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot',0777);
	    //写入新的文件.htaccess
	    $dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/alpha_plot/.htaccess';
	    $fp = fopen($dir, "w") or die("Unable to open file!");
	    $txt = "RewriteEngine on \n\nRewriteCond $1 !^(index\.php|html_plots|robots\.txt) \n\nRewriteRule ^(.*)$ /16s-report/index.php/$1 [L]";
		fwrite($fp, $txt);
		fclose($fp);
    }

    public function change_boxplot($dir,$groupname,$otuname){
    	//给alpha写入权限
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha',0777);	
    	$new_dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/boxplot_new.html';
    	$file = fopen($dir, "r");
		//输出文本中所有的行，直到文件结束为止。
		$newfile = fopen($new_dir, "w") or die("Unable to open file!");
		//写入新的文件
		while(!feof($file)){
			$content= fgets($file);
	    	if(strstr($content,"<script src=\"")){
	    		$str = str_replace("<script src=\"","<script src=\"<?php echo \$boxplot_url;?>/",$content);
				fwrite($newfile, $str);
	    	}else if(strstr($content,"<link href=\"")){
	    		$str = str_replace("<link href=\"","<link href=\"<?php echo \$boxplot_url;?>/",$content);
				fwrite($newfile, $str);
	    	}else if(strstr($content,",\"browser\":{\"width\":\"100%\",\"height\":400,\"padding\":40,\"fill\":true}")){
	    		$str = str_replace(",\"browser\":{\"width\":\"100%\",\"height\":400,\"padding\":40,\"fill\":true}","",$content);
				fwrite($newfile, $str);
	    	}else{
	    		fwrite($newfile, $content);
	    	}
	    }
	    fclose($file);
	    fclose($newfile);
	    
	    //boxplot_files
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/boxplot_files',0777);	
		//在boxplot_files文件下写入新的文件.htaccess
	    $dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/boxplot_files/.htaccess';
	    $fp = fopen($dir, "w") or die("Unable to open file!");
	    $txt = "RewriteEngine on \n\nRewriteCond $1 !^(index\.php|htmlwidgets-0.9|jquery-1.11.3|plotly-binding-4.7.1|plotlyjs-1.29.2|typedarray-0.1|robots\.txt) \n\nRewriteRule ^(.*)$ /16s-report/index.php/$1 [L]";
		fwrite($fp, $txt);
		fclose($fp);

		//给crosstalk-1.0.0写入权限
    	chmod($this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/boxplot_files/crosstalk-1.0.0',0777);
		//在crosstalk-1.0.0文件下写入新的文件.htaccess
	    $dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/boxplot_files/crosstalk-1.0.0/.htaccess';
	    $fp = fopen($dir, "w") or die("Unable to open file!");
	    $txt = "RewriteEngine on \n\nRewriteCond $1 !^(index\.php|css|js|robots\.txt) \n\nRewriteRule ^(.*)$ /16s-report/index.php/$1 [L]";
		fwrite($fp, $txt);
		fclose($fp);
    }

    public function alpha_columndata($groupname,$otuname){
    	$dir = $this->data_url.$this->group_id.'/group/'.$groupname."/".$otuname.'/alpha/summary.alpha_diversity.xls';
    	$file = fopen($dir, "r");
		//输出文本中所有的行，直到文件结束为止。
		$line = 0;
		while(!feof($file)){
			$content= fgets($file);
			if($content){
				$arr = explode("\t", $content);
				$data[] = $arr;
				if($line){
					$new_data['column'][] = $arr[0];

				}else{
		    		$new_data['categories']	= array_values(array_filter($arr));
		    	}
		    	$line++;
			}
			
	    }
	    fclose($file);
	    //$new_data['column'] = array_values($new_data['column']);
	    foreach ($data as $index => $arr) {
	    	if($index){
	    		foreach ($arr as $key => $value) {
	    			if($key){
	    				$i = $new_data['categories'][$key-1];
	    				$new_data['data'][$i][] = $value;
	    			}
	    		}
	    	}
	    }
		return $new_data;
//print_r($data);
	    die(print_r($new_data));
    }

}   