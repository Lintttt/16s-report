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
                    <!-- <a type="button"  class="btn btn-success" data-hover="tooltip" data-original-title="Download" style="float: right;margin-right: 30px;" href="<?php echo base_url('home/index')?>">分组列表</a> -->
                </div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-left">
                <li><i class="fa fa-home"></i>&nbsp;<a href="../index">分组方案</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li><a href="#"><?php echo $name?></a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li class="active">新增otu</li>
            </ol>
            <div class="clearfix"></div>
        </div>
	<div class="page-content">
        <div class="panel panel-green">
            <div class="panel-heading">新增otu</div>
              <div class="panel-body pan" style="margin: 20px;">
                <div id="app">
                <el-form :model="myForm" :rules="rules" ref="myForm" label-width="100px" class="myForm">
                  <el-form-item label="otu名称" prop="name">
                    <el-input v-model="myForm.name" id="myname"></el-input>
                  </el-form-item>
                  <table class="table otu_add_table" style="border: 0px solid transparent !important;">
                    <tr>
                      <td style="width:30px;text-align: right;"><el-checkbox v-on:change="depthstate_change" v-model="myForm.state.depthstate"></el-checkbox></td>
                      <td>
                        <label style="font-size: 15px;">抽平：</label>
                        <el-radio-group v-model="myForm.depth.data" v-on:change="depthchange" v-bind:disabled="!myForm.state.depthstate">
                          <el-radio label="min">最小丰度</el-radio>
                          <el-radio label="自定义"></el-radio>
                        </el-radio-group>
                        <el-select
                        v-bind:class="{ hidden:myForm.depth.class }"
                        v-model="myForm.depth.input"
                        filterable
                        allow-create
                        placeholder="可选择推荐数值,或填写">
                        <el-option
                          v-for="item in depthinput"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                      </el-select>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:30px;text-align: right;"><el-checkbox v-on:change="speciesstate_change" v-model="myForm.state.speciesstate"></el-checkbox></td>
                      <td>
                        <label style="font-size: 15px;">物种：</label>
                        <div v-for="(todo,index) in myForm.species"
                            v-bind:key="todo.id"
                            style="margin-top: 5px;" 
                        >
                            <el-select v-model="myForm.select1" placeholder="请选择" style="width: 100px;" v-bind:disabled="!myForm.state.speciesstate">
                                <el-option
                                  v-for="item in select1"
                                  :key="item.key"
                                  :label="item.value"
                                  :value="item.key"
                                  >
                                </el-option>
                              </el-select>
                              <el-select v-model="myForm.species[index].select2" placeholder="请选择" style="width: 150px;margin-left: 20px;" v-bind:disabled="!myForm.state.speciesstate" v-on:change="select2change(myForm.species[index].select2,index)">
                                <el-option
                                  v-for="item in select2"
                                  :key="item.key"
                                  :label="item.value"
                                  :value="item.value"
                                >
                                </el-option>
                              </el-select>
                              <label style="margin-left: 20px;">水平分类的</label>
                                <!-- <el-input style="width: 150px;" v-model="myForm.species[index].input1" v-bind:disabled="!myForm.state.speciesstate"></el-input> -->
                                <el-select filterable  v-model="myForm.species[index].input1" placeholder="请选择" style="width: 150px;margin-left: 20px;" v-bind:disabled="!myForm.state.speciesstate">
                                  <el-option
                                    v-for="item in myForm.species[index].select3"
                                    :key="item.key"
                                    :label="item.value"
                                    :value="item.value"
                                  >
                                  </el-option>
                                </el-select>

                              <el-button-group style="margin-left: 20px;margin-top: 3px;" >
                                <el-button type="primary" icon="plus" size="small" v-on:click="add" v-bind:class="[!index? show : hidden]" v-bind:disabled="!myForm.state.speciesstate"></el-button>
                                <el-button type="danger" icon="circle-cross" size="small" v-on:click="del(index)" v-bind:class="[index ? show : hidden]" v-bind:disabled="!myForm.state.speciesstate"></el-button>
                            </el-button-group>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:30px;text-align: right;"><el-checkbox v-on:change="absolutestate_change" v-model="myForm.state.absolutestate"></el-checkbox></td>
                      <td>
                        <label style="font-size: 15px;">绝对风度：</label>
                        <el-radio-group v-model="myForm.absolute.data" v-on:change="absolutechange" v-bind:disabled="!myForm.state.absolutestate">
                          <el-radio label="1"></el-radio>
                          <el-radio label="2"></el-radio>
                          <el-radio label="5"></el-radio>
                          <el-radio label="自定义"></el-radio>
                        </el-radio-group>
                        <el-input style="width: 150px;margin-left: 10px;" id="absolute_input" v-bind:class="{ hidden:myForm.absolute.class }" v-model="myForm.absolute.input"></el-input>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:30px;text-align: right;"><el-checkbox v-on:change="relativestate_change" v-model="myForm.state.relativestate"></el-checkbox></td>
                      <td>
                        <label style="font-size: 15px;">相对丰度：</label>
                        <el-radio-group v-model="myForm.relative.data" v-on:change="relativechange" v-bind:disabled="!myForm.state.relativestate">
                          <el-radio label="0.1">0.1%</el-radio>
                          <el-radio label="0.5">0.5%</el-radio>
                          <el-radio label="1">1%</el-radio>
                          <el-radio label="自定义"></el-radio>
                        </el-radio-group>
                        <el-input style="width: 150px;margin-left: 10px;" id="relative_input" v-bind:class="{ hidden:myForm.relative.class }" v-model="myForm.relative.input"></el-input>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:30px;text-align: right;"><el-checkbox v-on:change="topstate_change" v-model="myForm.state.topstate"></el-checkbox></td>
                      <td>
                        <label style="font-size: 15px;">Top：</label>
                        <el-radio-group v-model="myForm.top.data" v-on:change="topchange" v-bind:disabled="!myForm.state.topstate">
                          <el-radio label="10000"></el-radio>
                          <el-radio label="20000"></el-radio>
                          <el-radio label="50000"></el-radio>
                          <el-radio label="自定义"></el-radio>
                        </el-radio-group>
                        <el-input style="width: 150px;margin-left: 10px;" id="top_input" v-bind:class="{ hidden:myForm.top.class }" v-model="myForm.top.input"></el-input>
                      </td>
                    </tr>
                    <tr>
                      <td><el-checkbox v-model="checkAll" @change="CheckAll">全选</el-checkbox></td>
                      <td>
                        <el-button type="primary" @click="submitForm('myForm')">创建</el-button>
                        <el-button type="default" onclick="javascript:history.back(-1)">返回</el-button>
                      </td>
                    </tr>
                  </table>

                </el-form>   
            </div>
        </div> 
    </div>
    </div>
</div>
<script>
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
        show:'',
        hidden:'hidden',
        checkAll:false,
        myForm: {
          select1:'retain',
          name: <?php echo json_encode($name)?>+'.otutab.',
          state:{
            depthstate:false,
            speciesstate:false,
            absolutestate:false,
            relativestate:false,
            topstate:false,
          },
          depth:{
            data:'',
            class:true,
            input:'',
          },
          species:[
            
            {
                id:'1',
                select2:'Domain',
                input1:'',
                select3:'',
            },
            {
                id:'2',
                select2:'Phylum',
                input1:'',
                select3:'',
            }
          ],
          absolute:{
            data:'',
            class:true,
            input:'',
          },
          relative:{
            data:'',
            class:true,
            input:'',
          },
          top:{
            data:'',
            class:true,
            input:'',
          }
        },
        rules: {
          name: [
            { required: true, message: '请输入otu名称', trigger: 'blur' }
          ],
        }
    },
    mounted:function () {  
      this.myForm.species[0].select3 = this.selectlist['Domain'];
      this.myForm.species[1].select3 = this.selectlist['Phylum'];
    }, 
    methods:{
        depthstate_change:function(check){
          depthstate = this.myForm.state.depthstate;
          if(!depthstate){
            this.myForm.depth.data = '';
            this.cancalAll();
          }else{
            this.myForm.depth.data = 'min';
          }
          //this.myForm.state.depthstate = !depthstate;
        },
        speciesstate_change:function(check){
          speciesstate = this.myForm.state.speciesstate;
          if(!speciesstate){
            this.myForm.species.data = '';
            this.cancalAll();
          }
          //this.myForm.state.speciesstate = !speciesstate;
        },
        absolutestate_change:function(check){
          absolutestate = this.myForm.state.absolutestate;
          if(!absolutestate){
            this.myForm.absolute.data = '';
            this.cancalAll();
          }else{
            this.myForm.absolute.data = '1';
          }
          //this.myForm.state.absolutestate = !absolutestate;
        },
        relativestate_change:function(check){
          relativestate = this.myForm.state.relativestate;
          if(!relativestate){
            this.myForm.relative.data = '';
            this.cancalAll();
          }else{
            this.myForm.relative.data = '0.1';
          }
          //this.myForm.state.relativestate = !relativestate;
        },
        topstate_change:function(check){
          topstate = this.myForm.state.topstate;
          if(!topstate){
            this.myForm.top.data = '';
            this.cancalAll();
          }else{
            this.myForm.top.data = '10000';
          }
          //this.myForm.state.topstate = !topstate;
        },
        CheckAll() {
            checkbox__original = $(".el-checkbox__original");
            //console.log(checkbox__original.length);
            for(i=0;i<5;i++){
              switch(i){
                  case 0:
                      if(this.myForm.state.depthstate==this.checkAll){
                        console.log(this.myForm.state.depth);
                      }else{
                        checkbox__original[i].click();
                      }
                      break;
                  case 1:
                      if(this.myForm.state.speciesstate==this.checkAll){
                        console.log(this.myForm.state.species);
                      }else{
                        checkbox__original[i].click();
                      }
                      break;
                  case 2:
                      if(this.myForm.state.absolutestate==this.checkAll){
                        console.log(this.myForm.state.absolute);
                      }else{
                        checkbox__original[i].click();
                      }
                      break;
                  case 3:
                      if(this.myForm.state.relativestate==this.checkAll){
                        console.log(this.myForm.state.relative);
                      }else{
                        checkbox__original[i].click();
                      }
                      break;
                  case 4:
                      if(this.myForm.state.topstate==this.checkAll){
                        console.log(this.myForm.state.top);
                      }else{
                        checkbox__original[i].click();
                      }
                      break;
                  default:break;
                }
              }
        },
        cancalAll:function(){
          checkbox__original = $(".el-checkbox__original");
          checkAll = checkbox__original[5];
          if(checkAll.parentNode.className.indexOf('is-checked')>=0){
            //console.log(checkAll.parentNode.className);
            checkAll.parentNode.classList.remove('is-checked');
          }
        },
        select2change:function(label,index){
          //console.log(label,index);
          this.myForm.species[index].input1 = '';
          this.myForm.species[index].select3 = this.selectlist[label];
        },
        depthchange:function(label){
            if(label=='自定义'){
                this.myForm.depth.class = false;
            }else{
                this.myForm.depth.class = true;
            }
        },
        absolutechange:function(label){
            if(label=='自定义'){
                this.myForm.absolute.class = false;
            }else{
                this.myForm.absolute.class = true;
            }
        },
        relativechange:function(label){
            if(label=='自定义'){
                this.myForm.relative.class = false;
            }else{
                this.myForm.relative.class = true;
            }
        },
        topchange:function(label){
            if(label=='自定义'){
                this.myForm.top.class = false;
            }else{
                this.myForm.top.class = true;
            }
        },
        add:function(){
            var newone= new Object();
            newone['id'] = this.myForm.species.length+1;
            newone['select2'] = '';
            newone['input1'] = '';
            newone['select3'] = '';
            this.myForm.species.push(newone);

        },
        del:function(index){
            delete this.myForm.species[index];//删除的元素留空
            for(var j = 0 ;j<this.myForm.species.length;j++){  //删除数组中的空元素
                    if(this.myForm.species[j] == "" || typeof(this.myForm.species[j]) == "undefined"){  
                        this.myForm.species.splice(j,1);  
                        j= j-1;      
                    }  
                } 
        },
        submitForm(formName) {
            mess = this.$message;
            this.$refs[formName].validate((valid) => {
            if (valid) {
                if(this.checkname()){
                    if(this.checkform()){
                        //return false;
                        $.ajaxSetup({//设置ajax为同步
                            async: false
                        });
                         $.post("../ajax_otu_add",
                            {
                              form:this.myForm,
                              groupname:<?php echo json_encode($name);?>
                            },
                            function(data){
                                if(data.status=='1'){
                                  mess({
                                    message: data.data,
                                    type: 'success'
                                  });
                                  setTimeout(() => {
                                    window.location=data.ref;
                                  }, 1000);
                                }else{
                                  mess({
                                    message: data.data,
                                    type: 'error'
                                  });
                                }
                                
                            },'json');
                    }
                }               
            }else{
                console.log('error submit!!');
                return false;
            }
        });
      },
      checkname:function(){
        mess = this.$message;
        var flag;
        $.ajaxSetup({//设置ajax为同步
            async: false
        });
        $.post("../ajax_otu_checkname",
            {
                name:this.myForm.name,
                groupname:<?php echo json_encode($name);?>
            },
            function(data){
                if(data.status=='1'){
                    mess({
                        message: data.data,
                        type: 'error'
                    });
                    $("#myname input").focus();
                    flag =  false;
                }else{
                    flag =  true;
                }
            },'json');
        return flag;
      },
      checkform:function(){
        var mess = '请填写完整数据！';
        var model = '';
        var flag = false;
        if(this.myForm.depth.data == '自定义' && !this.myForm.depth.input){
            model = 'depth_input';
        }else if(this.myForm.absolute.data == '自定义' && !this.myForm.absolute.input){
            model = 'absolute_input';
        }else if(this.myForm.relative.data == '自定义' && !this.myForm.relative.input){
            model = 'relative_input';
        }else{
            if(this.myForm.top.data == '自定义' && !this.myForm.top.input){
                model = 'top_input';
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
            $("#"+model+" input").focus();
            return false;
        }
      },
    }
})
</script>