<!-- 引入element ui样式 -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url('static/js/element-ui/lib/theme-default/index.css')?>">
<!-- 引入Buttons-1.4.2样式 -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url('static/js/Buttons-1.4.2/css/buttons.dataTables.css')?>">
<!-- 引入vue -->
<script src="<?php echo base_url('static/js/vue/dist/vue.js')?>"></script>
<!-- 引入element ui组件库 -->
<script src="<?php echo base_url('static/js/element-ui/lib/index.js')?>"></script>
<!-- 引入Buttons-1.4.2插件以及相关js文件 -->
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/dataTables.buttons.js')?>"></script>
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/buttons.html5.js')?>"></script>
<script src="<?php echo base_url('static/js/Buttons-1.4.2/js/buttons.flash.js')?>"></script>
<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script> -->

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
                <li><i class="fa fa-home"></i>&nbsp;<a href="../index">分组方案</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li><a href="#"><?php echo $name?></a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li class="active">otu列表</li>
            </ol>
            <div class="clearfix"></div>
        </div>
	<div class="page-content">
        <div id="app">
            <div class="panel panel-green">
                <div class="panel-heading">otu列表
                    <a type="button"  class="btn btn-primary" style="float: right;margin-right: 20px;margin-top: -7px;" href="../otu_add/<?php echo $name;?>">新增otu</a>

                </div>
                    <div class="panel-body pan" style="margin: 20px;">
                    <el-tag type="success">otu</el-tag>
                    <el-collapse ><!-- accordion -->
                        <el-collapse-item :title="todo.name" :name="index"
                            v-for="(todo,index) in otulist"
                            v-bind:key="index"
                        >
                            <my-component 
                                v-bind:id="index"
                                v-bind:name="todo.name"
                                v-bind:depth="todo.depth"
                                v-bind:species="todo.species"
                                v-bind:absolute="todo.absolute"
                                v-bind:relative="todo.relative"
                                v-bind:top="todo.top"
                                v-bind:otu="todo"
                                v-bind:select1="select1"
                                v-bind:select2="select2"
                                v-bind:selectlist="selectlist"
                                v-bind:depthinput="depthinput"
                                @myname-change="changename"
                                @otu-del="delotu"
                                @spe-add="addspecies"
                                @spe-del="delspecies"
                            >
                            </my-component>

                        </el-collapse-item>
                    </el-collapse>
                    <!-- <el-button type="default" style="margin-top: 10px;">
                        <a href="../index">分组方案</a>
                    </el-button> -->
                    <a href="../edit/<?php echo $name;?>"><el-button type="primary" style="margin-top: 10px;">
                        分组方案信息
                    </el-button></a>
                    <el-button type="default"  style="margin-top: 10px;" onclick="javascript:history.back(-1)">返回</el-button>
                    </div>
                </div>

                <div>

                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">

    Vue.component('my-component',{
        props: ['id','name','depth','species','absolute','relative','top','otu','select1','select2','depthinput','selectlist'],
        template:'<div v-bind:class="name">\
                    <div style="margin-top:15px;">\
                        <label>otu名称：</label>\
                        <el-input v-model="myname" disabled="disabled"></el-input>\
                    </div>\
                    <div style="margin-top:25px;">\
                        <el-checkbox v-on:change="depthstate_change" v-model="otu.state.depthstate"></el-checkbox>\
                        <label>是否抽平：</label>\
                        <el-radio-group v-model="depth.data" v-on:change="depthchange" v-bind:disabled="!otu.state.depthstate">\
                          <el-radio label="min">最小丰度</el-radio>\
                          <el-radio label="自定义"></el-radio>\
                        </el-radio-group>\
                        \
                        <el-select\
                        v-bind:id="{hidden:depthid }"\
                        v-bind:class="{ hidden:depth.class }"\
                        v-model="depth.input"\
                        filterable\
                        allow-create\
                        placeholder="可选择推荐数值,或填写">\
                        <el-option\
                          v-for="item in depthinput"\
                          :key="item.value"\
                          :label="item.label"\
                          :value="item.value">\
                        </el-option>\
                      </el-select>\
                    </div>\
                    <div style="margin-top:25px;">\
                    <el-checkbox v-on:change="speciesstate_change" v-model="otu.state.speciesstate"></el-checkbox>\
                    <label>物种：</label>\
                    <div v-for="(todo,index) in species"\
                        v-bind:key="todo.id"\
                    >\
                        <el-select v-model="otu.select1" placeholder="请选择" style="width: 100px;" v-bind:disabled="!otu.state.speciesstate">\
                            <el-option\
                              v-for="item in select1"\
                              :key="item.key"\
                              :label="item.value"\
                              :value="item.key"\
                              >\
                            </el-option>\
                          </el-select>\
                          <el-select v-model="species[index].select2" placeholder="请选择" style="width: 150px;margin-left: 20px;" v-bind:disabled="!otu.state.speciesstate" v-on:change="select2change(species[index].select2,index)">\
                            <el-option\
                              v-for="item in select2"\
                              :key="item.key"\
                              :label="item.value"\
                              :value="item.value">\
                            </el-option>\
                          </el-select>\
                          <label style="margin-left: 20px;">水平分类的</label>\
                            <el-select filterable  v-model="species[index].input1" placeholder="请选择" style="width: 150px;margin-left: 20px;" v-bind:disabled="!otu.state.speciesstate">\
                                  <el-option\
                                    v-for="item in species[index].select3"\
                                    :key="item.key"\
                                    :label="item.value"\
                                    :value="item.value"\
                                  >\
                                  </el-option>\
                                </el-select>\
                          <el-button-group style="margin-left: 20px;margin-top: 3px;">\
                            <el-button type="primary" icon="plus" size="small" v-on:click="addspe" v-bind:class="[!index? show : hidden]" v-bind:disabled="!otu.state.speciesstate"></el-button>\
                            <el-button type="danger" icon="circle-cross" size="small" v-on:click="delspe(index)" v-bind:class="[index ? show : hidden]" v-bind:disabled="!otu.state.speciesstate"></el-button>\
                        </el-button-group>\
                    </div>\
                  </div>\
                    <div style="margin-top:25px;">\
                        <el-checkbox v-on:change="absolutestate_change" v-model="otu.state.absolutestate"></el-checkbox>\
                        <label>绝对丰度：</label>\
                        <el-radio-group v-model="absolute.data" v-on:change="absolutechange" v-bind:disabled="!otu.state.absolutestate">\
                          <el-radio label="1"></el-radio>\
                          <el-radio label="2"></el-radio>\
                          <el-radio label="5"></el-radio>\
                          <el-radio label="自定义"></el-radio>\
                        </el-radio-group>\
                        <input style="width: 150px;margin-left: 10px;" v-bind:id="absoluteid" v-bind:class="{ hidden:absolute.class }" v-model="absolute.input"></input>\
                    </div>\
                    <div style="margin-top:25px;">\
                        <el-checkbox v-on:change="relativestate_change" v-model="otu.state.relativestate"></el-checkbox>\
                        <label>相对丰度：</label>\
                        <el-radio-group v-model="relative.data" v-on:change="relativechange" v-bind:disabled="!otu.state.relativestate">\
                          <el-radio label="0.1">0.1%</el-radio>\
                          <el-radio label="0.5">0.5%</el-radio>\
                          <el-radio label="1">1%</el-radio>\
                          <el-radio label="自定义"></el-radio>\
                        </el-radio-group>\
                        <input style="width: 150px;margin-left: 10px;" v-bind:id="relativeid" v-bind:class="{ hidden:relative.class }" v-model="relative.input"></input>\
                    </div>\
                    <div style="margin-top:25px;">\
                        <el-checkbox v-on:change="topstate_change" v-model="otu.state.topstate"></el-checkbox>\
                        <label>Top：</label>\
                        <el-radio-group v-model="top.data" v-on:change="topchange" v-bind:disabled="!otu.state.topstate">\
                          <el-radio label="10000"></el-radio>\
                          <el-radio label="20000"></el-radio>\
                          <el-radio label="50000"></el-radio>\
                          <el-radio label="自定义"></el-radio>\
                        </el-radio-group>\
                        <input style="width: 150px;margin-left: 10px;" v-bind:id="topid" v-bind:class="{ hidden:top.class }" v-model="top.input"></input>\
                    </div>\
                    <div style="margin-top:25px;">\
                        <el-checkbox v-model="checkAll" @change="CheckAll">全选</el-checkbox>\
                        <el-button type="primary" icon="circle-check"  v-on:click="sub" ></el-button>\
                        <el-button type="danger" icon="circle-close"  v-on:click="del" v></el-button>\
                    </div>\
                    <div style="margin:20px;">\
                        <table class="cell-border" v-bind:id="tableid" cellpadding="0" cellspacing="1" border="0"></table>\
                    </div>\
                </div>\
                ',
        data: function () {
            return {myname:this.name,show:[''],hidden:['hidden'],depthid:['depth_input'+this.id],absoluteid:['absolute_input'+this.id],relativeid:['relative_input'+this.id],topid:['top_input'+this.id],tableid:['table'+this.id],checkAll:false};
        },
        mounted:function () {  
            this.datatable_ready();
            this.depth.class=this.depth.class=='true'?true:false;
            this.absolute.class=this.absolute.class=='true'?true:false;
            this.relative.class=this.relative.class=='true'?true:false;
            this.top.class=this.top.class=='true'?true:false;

            this.otu.state.depthstate=this.otu.state.depthstate=='true'?true:false;
            this.otu.state.speciesstate=this.otu.state.speciesstate=='true'?true:false;
            this.otu.state.absolutestate=this.otu.state.absolutestate=='true'?true:false;
            this.otu.state.relativestate=this.otu.state.relativestate=='true'?true:false;
            this.otu.state.topstate=this.otu.state.topstate=='true'?true:false;
        }, 
        watch:{
            myname(val,oldval) {
                //console.log(oldval,val);
                var data={"id":this.id,"value":val};
                this.$emit('myname-change',data);//③组件内对myResult变更后向外部发送事件通知
            },
            
        },
        methods:{
            datatable_ready:function(){
                //console.log(this.otu.datatable);
                $('#'+this.tableid).DataTable({
                    "scrollX": true,
                    "data": this.otu.datatable,
                    "columns":this.otu.columns,
                    "dom": 'Bfrtip',
                    "buttons": [
                        'csv', 'excel'
                    ],
                });
            },
            depthchange:function(label){
                if(label=='自定义'){
                    this.depth.class = false;
                }else{
                    this.depth.class = true;
                }
            },
            absolutechange:function(label){
                if(label=='自定义'){
                    this.absolute.class = false;
                }else{
                    this.absolute.class = true;
                }
            },
            relativechange:function(label){
                if(label=='自定义'){
                    this.relative.class = false;
                }else{
                    this.relative.class = true;
                }
            },
            topchange:function(label){
                if(label=='自定义'){
                    this.top.class = false;
                }else{
                    this.top.class = true;
                }
            },
            depthstate_change:function(check){
              depthstate = this.otu.state.depthstate;
              if(!depthstate){
                this.otu.depth.data = '';
                this.cancalAll();
              }else{
                this.otu.depth.data = 'min';
              }
              //this.otu.state.depthstate = !depthstate;
            },
            speciesstate_change:function(check){
              speciesstate = this.otu.state.speciesstate;
              if(!speciesstate){
                this.otu.species.data = '';
                this.cancalAll();
              }
              //this.otu.state.speciesstate = !speciesstate;
            },
            absolutestate_change:function(check){
              absolutestate = this.otu.state.absolutestate;
              if(!absolutestate){
                this.otu.absolute.data = '';
                this.cancalAll();
              }else{
                this.otu.absolute.data = '1';
              }
              //this.otu.state.absolutestate = !absolutestate;
            },
            relativestate_change:function(check){
              relativestate = this.otu.state.relativestate;
              if(!relativestate){
                this.otu.relative.data = '';
                this.cancalAll();
              }else{
                this.otu.relative.data = '0.1';
              }
              //this.otu.state.relativestate = !relativestate;
            },
            topstate_change:function(check){
              topstate = this.otu.state.topstate;
              if(!topstate){
                this.otu.top.data = '';
                this.cancalAll();
              }else{
                this.otu.top.data = '10000';
              }
              //this.otu.state.topstate = !topstate;
            },
            CheckAll() {
                checkbox__original = $(".el-checkbox__original");
                for(i=0;i<5;i++){
                    j = i+this.id*6;
                  switch(i){
                      case 0:
                          if(this.otu.state.depthstate==this.checkAll){
                            console.log(this.otu.state.depthstate);
                          }else{
                            checkbox__original[j].click();
                          }
                          break;
                      case 1:
                          if(this.otu.state.speciesstate==this.checkAll){
                            console.log(this.otu.state.speciesstate);
                          }else{
                            checkbox__original[j].click();
                          }
                          break;
                      case 2:
                          if(this.otu.state.absolutestate==this.checkAll){
                            console.log(this.otu.state.absolutestate);
                          }else{
                            checkbox__original[j].click();
                          }
                          break;
                      case 3:
                          if(this.otu.state.relativestate==this.checkAll){
                            console.log(this.otu.state.relativestate);
                          }else{
                            checkbox__original[j].click();
                          }
                          break;
                      case 4:
                          if(this.otu.state.topstate==this.checkAll){
                            console.log(this.otu.state.topstate);
                          }else{
                            checkbox__original[j].click();
                          }
                          break;
                      default:break;
                    }
                  }
            },
            cancalAll:function(){
              checkbox__original = $(".el-checkbox__original");
              checkAll = checkbox__original[5+this.id*6];
              if(checkAll.parentNode.className.indexOf('is-checked')>=0){
                //console.log(checkAll.parentNode.className);
                checkAll.parentNode.classList.remove('is-checked');
              }
            },
            select2change:function(label,index){
              //console.log(label,index);
              this.otu.species[index].input1 = '';
              this.otu.species[index].select3 = this.selectlist[label];
            },
            checkform:function(){
                var mess = '请填写完整数据！';
                var model = '';
                var flag = false;
                if(this.depth.data == '自定义' && !this.depth.input){
                    model = 'depth_input'+this.id;
                }else if(this.absolute.data == '自定义' && !this.absolute.input){
                    model = 'absolute_input'+this.id;
                }else if(this.relative.data == '自定义' && !this.relative.input){
                    model = 'relative_input'+this.id;
                }else{
                    if(this.top.data == '自定义' && !this.top.input){
                        model = 'top_input'+this.id;
                    }else{
                        flag = true;
                    }
                }
                if(flag){
                    return true;
                }else{
                    this.$message({
                        message: mess,
                        type: 'error'
                    });
                    $("#"+model).focus();
                    return false;
                }
            },
            sub:function(){
                if(this.checkform()){
                    this.$confirm('此操作将修改该otu丰度表, 是否继续?', '提示', {
                      confirmButtonText: '确定',
                      cancelButtonText: '取消',
                      type: 'warning'
                    }).then(() => {
                        mess = this.$message;
                        otu = this.otu;
                        otu.datatable = '';
                        $.post("../ajaxsaveotu",{
                            otu:otu,
                            groupname:<?php echo json_encode($name);?>
                        },
                        function(data){
                            if(data.status){
                                mess({
                                  message: data.data,
                                  type: 'success'
                                });
                                window.location.reload();//刷新当前页面
                            }else{
                                mess({
                                  message: data.data,
                                  type: 'error'
                                });
                            }
                        },'json');
                    }).catch(() => {
                      this.$message({
                        type: 'info',
                        message: '已取消修改'
                      });          
                    });
                }
            },
            del:function(){
                this.$confirm('此操作将永久删除该otu丰度表, 是否继续?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning'
                }).then(() => {
                    $(".el-collapse-item.is-active").children("div .el-collapse-item__header").click();
                    mess = this.$message;
                    $.post("../ajaxdelotu",{
                        name:this.otu.name,
                        groupname:<?php echo json_encode($name);?>
                    },
                    function(data){
                        if(data.status){
                            mess({
                              message: data.data,
                              type: 'success'
                            });
                        }else{
                            mess({
                              message: data.data,
                              type: 'error'
                            });
                        }
                    },'json');
                    this.$emit('otu-del',this.id);//③组件内对myResult变更后向外部发送事件通知
                }).catch(() => {
                  this.$message({
                    type: 'info',
                    message: '已取消删除'
                  });          
                });
            },
            addspe:function(){
                this.$emit('spe-add',this.id);//③组件内对myResult变更后向外部发送事件通知
            },
            delspe:function(index){
                var data={'id':this.id,'index':index};
                this.$emit('spe-del',data);//③组件内对myResult变更后向外部发送事件通知
            }
        }
    })
var vue = new Vue({
    el:'#app',
    data:{
        depthinput:[
          {
            value:'30000',
            label:'30000'
          },
          {
            value:'40000',
            label:'40000'
          },
          {
            value:'50000',
            label:'50000'
          }
        ],
        select1:<?php echo json_encode($select1)?>,
        select2:<?php echo json_encode($select2)?>,
        selectlist:<?php echo json_encode($selectlist)?>,
        otulist:<?php echo json_encode($otu);?>
    },
    methods:{
        changename:function(data){
            this.otulist[data.id].name = data.value;
        },
        delotu:function(data){
            delete this.otulist[data];//删除的元素留空
            for(var j = 0 ;j<this.otulist.length;j++){  //删除数组中的空元素
                if(this.otulist[j] == "" || typeof(this.otulist[j]) == "undefined"){  
                    this.otulist.splice(j,1);  
                    j= j-1;      
                }  
            } 
            if(this.otulist.length==0){
                this.$notify.warning({
                  title: '警告',
                  message: '没有otu丰度表！请先添加！',
                  offset: 100
                });
            }
        },
        addspecies:function(data){
            var newone= new Object();
            console.log(this.otulist[data]);
            console.log(this.otulist[data].species);
            newone['id'] = this.otulist[data].species.length+1;
            newone['select2'] = '';
            newone['input1'] = '';
            newone['select3'] = '';
            this.otulist[data].species.push(newone);
        },
        delspecies:function(data){
            delete this.otulist[data.id].species[data.index];//删除的元素留空
            for(var j = 0 ;j<this.otulist[data.id].species.length;j++){  //删除数组中的空元素
                    if(this.otulist[data.id].species[j] == "" || typeof(this.otulist[data.id].species[j]) == "undefined"){  
                        this.otulist[data.id].species.splice(j,1);  
                        j= j-1;      
                    }  
                } 
        },
    }
})
</script>