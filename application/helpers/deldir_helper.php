<?php
/**
 * 删除文件夹
 *
 * @access: public
 * @author: linting
 * @param: 	dir，文件夹路径
 * @return: boolean
 */
	function deldir($dir) {//删除文件函数
	//rmdir()函数只能删除空目录，如果是非空目录就需要先进入到目录中，使用unlink()函数将目录中的每个文件都删除掉，再回来将这个空目录删除
	$dh=opendir($dir);//打开目录
	while ($file=readdir($dh)) {//读取目录中文件名
	    if($file!="." && $file!="..") {
	    	$fullpath=$dir."/".$file;
	        if(!is_dir($fullpath)) {//文件名存在并且不是目录
	            unlink($fullpath);//删除文件
	        } else {
	            deldir($fullpath);//调用函数，删除该目录
	        }
	    }
	}
	closedir($dh);
	//删除当前文件夹：
	if(rmdir($dir)) {
	    return true;
	} else {
	    return false;
	}
}