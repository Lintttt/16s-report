<!-- 引入element ui样式 -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url('static/js/element-ui/lib/theme-default/index.css')?>">
<!-- 引入vue -->
<script src="<?php echo base_url('static/js/vue/dist/vue.js')?>"></script>
<!-- 引入element ui组件库 -->
<script src="<?php echo base_url('static/js/element-ui/lib/index.js')?>"></script>

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
                <li class="active">新增分组</li>
            </ol>
            <div class="clearfix"></div>
        </div>
	<div class="page-content">
		<div class="panel panel-green">
            <div class="panel-heading">新增分组
            	<a type="button"  class="btn btn-primary" style="float: right;margin-right: 30px;margin-top: -7px;" href="<?php echo base_url('home/index')?>">分组列表</a>
            </div>
            <div class="panel-body pan" style="margin: 20px;">
            <?php echo validation_errors(); ?>
            <?php echo form_open('',"id='myform'"); ?>
			<div id="app">
                <!-- <el-form :model="groupform" :rules="rules" ref="groupform" > -->
                <div class="form-group">
                	<el-tag type="success">分组名称</el-tag>
                    <div class="input-icon right">
                        <i class="fa fa-tag"></i>
                        <input v-model="group_name" class="form-control" id="groupname" name="groupname">
                    </div>
                </div> 

                <div class="form-group">
                	<el-tag type="success">分组信息</el-tag>
            		<el-collapse accordion><!-- accordion -->
						<el-collapse-item :title="todo.title" :name="todo.id"
							v-for="(todo,index) in group"
							v-bind:key="todo.id">
							<my-component 
					      		v-bind:index="todo.id"
					      		v-bind:title="todo.title"
					      		v-bind:samplelist="todo.samplelist"
					      		v-bind:check="todo.check"
					      		@mytitle-change="changetitle"
					      		@group-change="changegroup">
							</my-component>
						</el-collapse-item>
					</el-collapse>
					<div style="margin-top: 10px;margin-bottom: 10px;">
						<el-button-group>
						  <el-button type="primary" icon="plus" v-on:click="addgroup"></el-button>
						  <el-button type="danger" icon="delete" v-on:click="delgroup"></el-button>
						  <el-button type="success" v-on:click="successgroup">分组完成</el-button>
						</el-button-group>
					</div>
					<el-collapse-transition>
					<div style="margin-top: 20px;" v-show="show"><!-- display: none; -->
						<el-tag type="success">组间比较</el-tag>
						<my-select 
							v-for="(todo,index) in selectop" 
							v-bind:options="options" 
							v-bind:key="todo.id"
							v-bind:index="index"
							v-bind:compareclass="todo"
							@add="addcompare"
							@del="delcompare"
							@select-change="changeselect">
						</my-select>

						<div>
                        	<el-button  type="primary"  @click="submitForm()">提交</el-button>
                    	</div>
					</div>
					</el-collapse-transition>
                </div>
                </form>
			</div>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function sortBy (attr,rev){
    //第二个参数没有传递 默认升序排列
	    if(rev ==  undefined){
	        rev = 1;
	    }else{
	        rev = (rev) ? 1 : -1;
	    }
	    return function(a,b){
	        a = a[attr];
	        b = b[attr];
	        if(a < b){
	            return rev * -1;
	        }
	        if(a > b){
	            return rev * 1;
	        }
	        return 0;
	    }
	};
</script>
<script type="text/javascript">
	Vue.component('my-component', {
		props: ['index','title','samplelist','activeclass','check'],
		template: 	'<div><div style="width: 30%;float: left;">\
						<br><br>\
						<label>分组名称：</label>\
						<input v-model="mytitle" name="fenzuname[]">\
					</div>\
					<div style="width: 70%;float: left;">\
					    <template>\
							<el-transfer\
							    filterable\
							    :titles=\'["全部样品","已选样品"]\'\
							    filter-placeholder="请输入......"\
							    v-model="allsample"\
							    @change="handleChange"\
							    :data="samplelist"\
							    name=select[]\
							>\
							</el-transfer>\
						</template>\
					</div></div>\
					',
		data: function () {
		    return {mytitle:this.title,allsample:this.check};
		},
		watch: {
			mytitle(val) {
				var data={"id":this.index,"value":val};
				this.$emit('mytitle-change',data);//③组件内对myResult变更后向外部发送事件通知
			}
		},
		methods:{
			handleChange(value, direction, movedKeys){
				var data={"id":this.index,"value":value,"direction":direction,"movedKeys":movedKeys};
				this.$emit('group-change',data);
	 		}
		}
	})
	Vue.component('my-select',{
		props: ['compareclass','options','index'],
		template:'<div v-bind:class="compareclass.id" style="width:100%;height:50px;">\
					<div style="float:left;">\
						<el-select v-model="selectvalue1" filterable placeholder="请选择" @change="handleChange1">\
						    <el-option\
						      v-for="item in options"\
						      :key="item.label"\
						      :label="item.label"\
						      :value="item.label">\
						    </el-option>\
						</el-select>\
					</div>\
					<div style="float:left;">\
					<font style="font-size: 20px;color: black;margin: 25px;">VS</font>\
					</div>\
					<div style="float:left;">\
						<el-select v-model="selectvalue2" filterable placeholder="请选择" @change="handleChange2">\
						    <el-option\
						      v-for="item in options"\
						      :key="item.label"\
						      :label="item.label"\
						      :value="item.label">\
						    </el-option>\
						</el-select>\
					</div>\
					<div style="float:left;">\
						<el-button-group style="margin-left: 20px;margin-top: 3px;">\
							<el-button type="primary" icon="plus" size="small" v-on:click="add" v-bind:class="[!index ? show : hidden]"></el-button>\
							<el-button type="danger" icon="circle-cross" size="small" v-on:click="del" v-bind:class="[index ? show : hidden]"></el-button>\
						</el-button-group>\
					</div>\
				</div>\
				',
		data: function () {
		    return {selectvalue1:[],selectvalue2:[],show:[''],hidden:['hidden']};
		},
		methods:{
	 		add:function(){
	 			this.$emit('add');
	 		},
	 		del:function(){
	 			var data = this.compareclass['id'];
				this.$emit('del',data);
	 		},
	 		handleChange1:function(value){
	 			var data={"id":this.index,"index":0,"value":value};
	 			this.$emit('select-change',data);
	 		},
	 		handleChange2:function(value){
	 			var data={"id":this.index,"index":1,"value":value};
	 			this.$emit('select-change',data);
	 		}
		}
	})
	var vue = new Vue({
		el: '#app',
		data: {
	  		group_name: 'GroupName',
	  		allsample:[],
        	alldata: <?php echo json_encode($data);?>,
        	checkeddata: '',	
        	group:[
        		{
        			id:1,
        			title:'group1',
        			samplelist:<?php echo json_encode($data);?>,
        			check:[]
        		},
        		{
        			id:2,
        			title:'group2',
        			samplelist:<?php echo json_encode($data);?>,
        			check:[]
        		}
        	],
        	selectop:[
        		{
        			id:'1',
        			checkone:'',
        			checktwo:'',
        		}
        	],
        	options:[
        		{
        			label:'group1'
        		},
        		{
        			label:'group2'
        		},
        	],
        	show:false
	  	},
		methods: {
	 		changetitle:function(data){
	 			this.group[data.id-1].title = data.value;
	 		},
	 		changegroup:function(data){
	 			var group = this.group;
	 			if(data.direction=='right'){//向右
	 				for(var i=data.id;i<group.length;i++){//循环group
	 					var pre = group[i].samplelist;
	 					pre.forEach(function(v,index,arr){//遍历每一个samplelist
	 						var result = $.inArray(v.key,data.movedKeys);
	 						if(result>=0){
	 							delete pre[index];//删除的元素留空
	 						}
	 					});
	 					for(var j = 0 ;j<pre.length;j++){  //删除数组中的空元素
				            if(pre[j] == "" || typeof(pre[j]) == "undefined"){  
				                pre.splice(j,1);  
				                j= j-1;      
				            }  
				        } 
				        if(group[i].check){
				        	group[i].check.forEach(function(v,index,arr){
				        		var result = $.inArray(v,data.movedKeys);
		 						if(result>=0){
		 							delete group[i].check[index];//删除的元素留空
		 						}
				        	});
				        	for(var j = 0 ;j<group[i].check.length;j++){  //删除数组中的空元素
					            if(group[i].check[j] == "" || typeof(group[i].check[j]) == "undefined"){  
					                group[i].check.splice(j,1);  
					                j= j-1;      
					            }  
					        } 
				        }
					}
	 			}else{//向左
	 				for(var i=data.id;i<group.length;i++){//循环group
	 					var pre = group[i].samplelist;
	 					data.movedKeys.forEach(function(v,index,arr){//遍历每一个samplelist
	 						var newkey= new Object();
	 						newkey.key = v;
	 						pre.unshift(newkey);//新增元素
	 						pre.sort(sortBy('key'));//根据key值排序
	 					});
	 					group[i].samplelist = pre;//重新赋值
					}
	 			}
	 			//记录已选的值
	 			var checkgroup = [];
	 			data.value.forEach(function(val,index,arr){
	 				checkgroup.push(val);
	 			});
	 			this.group[data.id-1].check = checkgroup;
	 		},
	 		addgroup:function(){
	 			this.show = false;
	 			var group = this.group;
	 			var i = group.length;
	 			var newgroup= new Object();
	 			newgroup['id'] = (i+1) ;
	 			newgroup['title'] = 'group'+(i+1) ;
	 			newgroup['check'] = new Array();
	 			var check = $(".el-transfer-panel").last().attr('value');//最后一个group所选的值
				var arr = check.split(",");
	 			var pre = $.extend(true,[],group[(i-1)].samplelist);//number,string类型都是基本类型，而基本类型存放在栈区，访问时按值访问，赋值是按照普通方式赋值；对象和数组是通过引用来赋值的，所以改变a的同时b也会跟着改变
	 			pre.forEach(function(v,index){//遍历samplelist
	 				var result = $.inArray(v.key,arr);
	 				if(result>=0){
	 					delete pre[index];//删除的元素留空
	 				}
	 			});
	 			for(var j = 0 ;j<pre.length;j++){  //删除数组中的空元素
				    if(pre[j] == "" || typeof(pre[j]) == "undefined"){  
				        pre.splice(j,1);  
				        j= j-1;      
				    }  
				} 
	 			newgroup['samplelist'] = pre;
	 			if(pre.length){
	 				this.group.push(newgroup);
	 			}else{
	 				this.$message({
			          message: '样品已选完！',
			          type: 'warning'
			        });
	 			}
	 			
	 		},
	 		delgroup:function(){
	 			var group = this.group;
	 			var i = group.length;
	 			if(i>2){
	 				group.pop();
	 				this.options = this.updateselectoptions();
	 			}else{
			        this.$message({
			          message: '不能删除'+this.group[0].title+','+this.group[1].title+'！',
			          type: 'warning'
			        });
	 			}
	 		},
	 		successgroup:function(){
	 			if(this.checkgroup()){
	 				this.options = this.updateselectoptions();
		 			this.$message({
			          message: '分组已完成，请进行组间比较！',
			          type: 'success'
			        });
			        $(".el-collapse-item.is-active").children("div .el-collapse-item__header").click();
			        this.show = true;
	 			}
	 		},
	 		checkgroup:function(){
	 			var mess = this.$message;
	 			var flag = 0;
	 			for(var i=0;i<this.group.length;i++){//循环group
	 				if(this.group[i].check.length!=0){
	 					flag = 1;
	 				}else{
	 					flag = 0;
	 					break;
	 				}
				}
				$("div .el-collapse-item__header").eq(i).click();
	 			if(!flag){
	 				this.$message({
			          message: '分组不能为空，请选择样品！',
			          type: 'error'
			        });
			        return false;
	 			}else{
	 				return true;
	 			}
	 		},
	 		updateselectoptions:function(){
	 			var options = this.options;
	 			options = [];//清空数组 
	 			this.group.forEach(function(v,index){
	 				var op= new Object();
	 				op['label'] = v.title;
	 				options.push(op);
	 			});
	 			return options;
	 		},
	 		addcompare:function(){
	 			var selectop = this.selectop;
	 			var i = selectop.length+1;
	 			var newselectop= new Object();
	 			newselectop['id'] =  i.toString() ;
	 			this.selectop.push(newselectop);
			},
	 		delcompare:function(data){
	 			var selectop = this.selectop;
	 			selectop.forEach(function(v,index){
	 				if(v.id == data && index != 0){
	 					delete selectop[index];//删除的元素留空
	 				}
	 			});
	 			for(var j = 0 ;j<selectop.length;j++){  //删除数组中的空元素
				    if(selectop[j] == "" || typeof(selectop[j]) == "undefined"){  
				        selectop.splice(j,1);  
				        j= j-1;      
				    }  
				} 
	 			this.selectop = selectop;
	 		},
	 		changeselect:function(data){
	 			var selectop = this.selectop;
	 			if(data.index){
	 				selectop[data.id].checktwo=data.value;
	 			}else{
	 				selectop[data.id].checkone=data.value;
	 			}
	 		},
	 		checkselect:function(){
	 			var selectop = this.selectop;
	 			var flag = 0;
	 			selectop.forEach(function(v,index,arr){
	 				if(v.checkone && v.checktwo){
	 					flag = 1;
	 				}else{
	 					flag = 0;
	 				}
	 			});
	 			if(flag){
	 				return true;
	 			}else{
	 				this.$message({
			          message: '组间比较不能为空，请完成组间比较！',
			          type: 'error'
			        });
			        return false;
	 			}
	 		},
	 		submitForm:function(){
	 			if(this.checkform()){	
	 				var mess = this.$message;
	 				var fenzu = this.group;
	 				var fenzuname = [];
	 				var fenzucheck = [];
	 				fenzu.forEach(function(v,index,arr){
	 					fenzuname.push(v.title);
	 					fenzucheck.push(v.check);
	 				});
	 				//console.log(fenzuname,fenzucheck);
	 				var selectop = this.selectop;
	 				var selectall = [];
	 				selectop.forEach(function(v,index,arr){
	 					var op = [v.checkone,v.checktwo];
	 					selectall.push(op);
	 				});
	 				//console.log(selectall);
	 				$.post("grouping",
		            {   type:'create',
		                groupname:this.group_name,
		                fenzuname:fenzuname,
		                fenzucheck:fenzucheck,
		                selectall:selectall,
		            },
		            function(data){
		                if(data.status){
		                	mess({
					          message: data.data,
					          type: 'success'
					        });
					        setTimeout(
					        	function(){
					        		window.location=data.ref;
					        }, 1000);
		                }else{
		                    alert(data.data);
		                }
		            },'json');
	 			}
	 		},
	 		checkform:function(){
	 			if(!this.checkname()){
	 				return false;
	 			}else{
	 				if(!this.checkgroup()){
		 				return false;
		 			}else{
		 				if(!this.checkselect()){
			 				return false;
			 			}
			 			else{
			 				return true;
			 			}
		 			}
	 			}
	 		},
	 		checkname:function(){
	 			var groupname = this.group_name;
	 			var mess = this.$message;
	 			var flag = '';
	 			if(!groupname){
	 				mess({
			          message: '分组名称不能为空！',
			          type: 'error'
			        });
			        $("#groupname").focus();
	 				flag = false;
	 			}else{
	 				$.ajaxSetup({//设置ajax为同步
				      async: false
				    });
	 				$.post("ajax_checkname",
			        {
			          name:groupname
			        },
			        function(data){
			            if(!data.status){
			                mess({
					          message: data.data,
					          type: 'error'
					        });
					        $("#groupname").focus();
					        flag = false;
			            }else{
			            	flag = true;
			            }
			        },'json');

			        return flag;
	 			}
	 		}
	 	}
	});
</script>