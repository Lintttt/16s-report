				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    	var grouplist=$("#grouplist option:last").attr("value");
        if(!grouplist){
            alert("暂时没有分组方案，请先创建！");
        }
    });
</script>
<script type="text/javascript">
    var src = '';
    $("#grouplist").on('change',function(){
        $.post('./ajax_getotulist',
        {
            name:this.value,
        },
        function(data){
            $("#otulist").html('');
            if(data.status=='1'){
                $.each(data.data,function(i,v){
                    $("#otulist").append("<option value='"+v+"'>"+v+"</option>");
                })
            }else{
                alert(data.data);
            }
        },'json');
    });
    $("#form_submit").on('click',function(){
    	if($("#grouplist").attr("value") && $("#otulist").attr("value")){
    		$("#form_submit").attr("disabled", true);
	        $("#form_submit").removeClass("btn-primary");
	        $("#form_submit").addClass("btn-success");
	        $("#form_submit").attr("value","分析中......");
	        $("#myform").submit();
    	}else{
    		alert("请保证分组方案和OTU丰度表都不为空！");
    	}
    })
</script>