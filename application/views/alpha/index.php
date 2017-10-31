<!-- 引入Buttons-1.4.2样式 -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url('static/js/Buttons-1.4.2/css/buttons.dataTables.css')?>">
<!-- 引入Buttons-1.4.2插件以及相关js文件 -->
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/dataTables.buttons.js')?>"></script>
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/buttons.html5.js')?>"></script>
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/buttons.flash.js')?>"></script>
<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<?php $this->load->view("public/topbar"); ?>
<div id="wrapper">
<?php $this->load->view("public/sidebar"); ?>
    <div id="page-wrapper">
		<!--BEGIN TITLE & BREADCRUMB PAGE-->
        <div class="page-title-breadcrumb option-demo">
            <div class="page-header pull-right">
                <div class="page-toolbar">
                    
                </div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-left">
                <li><i class="fa fa-home"></i>&nbsp;<a href="index">Alpha多样性分析</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li class="active">Alpha参数</li>
            </ol>
            <div class="clearfix">
            </div>
        </div>
		<!--END TITLE & BREADCRUMB PAGE-->
        <div class="page-content" id="app">
			<div id="tab-general">
				<div class="row mbl">
	                <div class="col-lg-12">
	                    <div class="col-md-12">
	                        <div id="area-chart-spline" style="width: 100%; height: 300px; display: none;"></div>
	                    </div>
	                </div>

	                <div class="col-lg-12">
	                    <div class="row">
	                        <div class="panel">
	                            <div class="panel-heading">Alpha参数选择</div>
	                            <div class="panel-body">
	                             	<?php echo validation_errors(); ?>
									<?php echo form_open('alpha/index',"id='myform'"); ?>
										<div class="form-body pal">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="group" class="control-label">分组方案</label>
                                                        <select class="form-control" id="grouplist" name="groupname">
                                                        	<?php 
                                                        		foreach($grouplist as $value){
                                                        			if($value == $groupname){
                                                        	?>
                                                            <option value="<?php echo $value?>" selected="selected"><?php echo $value?></option>
															<?php 
																	}else{
															?>
															<option value="<?php echo $value?>"><?php echo $value?></option>
															<?php
																	}
																}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="otu" class="control-label">OTU丰度表</label>
                                                        <select class="form-control" id="otulist" name="otuname">
                                                            <?php 
                                                        		foreach($otulist as $value){
                                                        			if($value == $otuname){
                                                        	?>
                                                            <option value="<?php echo $value?>" selected = "selected"><?php echo $value?></option>
															<?php 
																	}else{
															?>
															<option value="<?php echo $value?>"><?php echo $value?></option>
															<?php
																	}
																}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-left pal col-md-6" >
                                           <input type="button" id="form_submit" class="btn btn-primary" value="提交">
                                        </div>
	                             	</form>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>

	            <div class="row mbl">
	                
	                            	

