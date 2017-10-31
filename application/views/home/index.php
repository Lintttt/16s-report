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
                <li><i class="fa fa-home"></i>&nbsp;<a href="index">分组方案</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li class="active">分组列表</li>
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
                            <div id="area-chart-spline" style="width: 100%; height: 300px; display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="row">
                        <div class="panel">
                            <div class="panel-heading">所有分组 
                                <a type="button"  class="btn btn-success"  style="float: right;margin-right: 30px;margin-top:-5px;" href="create">新增分组</a>
                            </div>
                            <div class="panel-body">
                                <table class="table" style="text-align: center;">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%;text-align: center;">#</th>
                                        <th style="width: 40%;text-align: center;">分组名称</th>
                                        <th style="width: 30%;text-align: center;">otu表</th>
                                        <th style="width: 20%;text-align: center;">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                <?php if(!empty($group)) :?>
                                <?php foreach($group['name'] as $key=>$value):?>
                                    <tr class="<?php echo $value;?>">
                                        <td><?php echo $key+1;?></td>
                                        <td class="groupname"><?php echo $value;?></td>
                                        <td>
                                        <?php
                                            if($this->home->otu_exists($value)=='1'){
                                        ?>

                                            <a class="file_a" href="otu_edit/<?php echo $value;?>"><i class="fa fa-file fa-fw"></i></a>
                                        <?php
                                            }
                                        ?>
                                            
                                            <a class="add_a" href="otu_add/<?php echo $value;?>"><i class="fa fa-plus fa-fw"></i></a>
                                        </td>
                                        <td>
                                            <a class="edit_a" href="edit/<?php echo $value;?>" id="<?php echo 'edit'.$value;?>"><i class="fa fa-edit fa-fw"></i></a>
                                            <a class="delete_a" id="<?php echo 'delete_'.$value;?>"><i class="fa fa-times fa-fw"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach?>
                                <?php endif?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(".groupname").on('dblclick',function(){
    var val =$(this).text();
    $(this).html("<input type='text' name='' id='testname'></input>");
    $("#testname").focus().val(val);
    testname();
});
function testname(){
    $('#testname').on('blur',function(){  
        var val = $(this).val();
        var old_val = $(this).parent().parent().attr('class');
        var parent = $(this).parent();

        if(val!='' && val!=old_val){
            //ajax传值
            $.post("ajax_changename",
            {
                old:old_val,now:val
            },
            function(data){
                if(data.status){
                    parent.html('').text(val);
                    //改变相关的参数值
                    parent.parent().attr('class',val);
                    parent.parent().find(".search_a").attr('id','search_'+val);
                    parent.parent().find(".delete_a").attr('id','delete_'+val);
                }else{
                    alert('已存在该分组方案，请重新填写！');
                    parent.html('').text(old_val);  
                }
            },'json');
        }else{
            parent.html('').text(old_val);
        }
    });
    $("#testname").on('keypress',function(e){
        var eCode = e.keyCode?e.keyCode : e.which ? e.which : e.charCode;
        if (eCode == 13){
            $(this).blur();
        }
    })
}
//删除
$(".delete_a").on('click',function(){
    var ID = $(this).attr('id');
    var arr =ID.split('_');
    var name = arr[1];

    if(confirm('确定要执行此操作吗?')) 
    { 
        $.post("ajax_grouping_delete",
        {
          name:name
        },
        function(data){
            if(data.status){
                alert(data.data);
            }else{
                alert(data.data);
            }
        },'json');
        window.location.reload();//刷新当前页面
        return true; 
    } 
});
</script>
