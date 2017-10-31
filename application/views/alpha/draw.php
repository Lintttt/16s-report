                    <div class="col-lg-12" id="shannon_div" >
                        <div class="row">

                            <div class="panel">
                                <div class="panel-heading">
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#α多样性指数" data-toggle="tab">α多样性指数</a>
                                        </li>
                                        <li><a href="#稀释曲线" data-toggle="tab">稀释曲线</a></li>
                                        <li><a href="#差异分析" data-toggle="tab">差异分析</a></li>
                                    </ul>
                                </div>
                                <div class="panel-body" style="min-height: 600px;">
                                    <div id="myTabContent" class="tab-content">
                                        <div class="tab-pane fade in active" id="α多样性指数">
                                            <p>α多样性是指特定生境或者生态系统内的多样性情况，它可以指示生境被物种隔离的程度，通常利用物种丰富度（种类情况） 与物种均匀度（分布情况）两个重要参数来计算。 本项目结题报告主要展示Chao1，ACE，Shannon，observed_species，Simpson和Good’s Coverage六大类常用的 α多样性指数以及它们的相关分析结果。总体来说，Chao1 / ACE 指数主要关心样本的物种丰富度信息； Good’s Coverage反映样本的低丰度OTU覆盖情况；observed_species 表示能检测到的OTU种类情况； Simpson / Shannon 主要综合体现物种的丰富度和均匀度。</p>
                                        </div>
                                        <div class="tab-pane fade" id="稀释曲线">
                                            <p>我们通过绘制shannon稀释曲线（shannon rarefaction curve）来评价测序量是否足够，并间接反映样品中物种的丰富程度。 shannon指数作为一个评价样品内物种多样性程度的指标，值越高代表多样性程度越高。 shannon稀释曲线是通过抽样n个tags来计算shannon指数的期望值，然后根据一组n值（一般为一组小于总序列数的等差数列） 与其相对应的 shannon的期望值做出曲线来，当曲线趋于平缓或者达到平台期时也就可以认为测序深度增加已经不影响物种 多样性，测序量趋于饱和。</p>
                                        </div>
                                        <div class="tab-pane fade" id="差异分析">
                                            <p>在进行差异分析的同时，我们还绘制了各个Alpha多样性指数的箱线图（下图展示了各个比较组的shannon指数箱线图）。 箱形图可以直观的反应组内物种多样性的中位数、离散程度、最大值、最小值、异常值， 箱线图的详细解读可以查看wiki百科的说明。</p>
                                        </div>
                                    </div>
                                    <div id="container" style="min-height:400px;width: 100%;">

                                        
                                    </div>
                                   <!--  <div id="svg" style="min-width:400px;"></div> -->
                                    <div style="margin-top: 20px;display: none;" id="show_div">
                                        <?php include_once($src);?>
                                    </div>
                                    <div style="margin-top: 20px;display: none;" id="mychart">
                                        <?php include_once($src_boxplot);?>
                                    </div>

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
    $(document).ready(function(){
        $("#mychart").hide();
        draw();
    });
    $(function(){
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // 获取已激活的标签页的名称
            var activeTab = $(e.target).text(); 
            
            if(activeTab=="α多样性指数"){
                
                $("#mychart").hide();
                $("#show_div").hide();
            }else if(activeTab=="稀释曲线"){
                changeselect(4,2);
                $("#mychart").hide();
                $("#show_div").show();  
            }else{
                $("#show_div").hide();
                $("#mychart").show();
            }
        });
    });
    function changeselect(a,b){
        var obj1 = document.getElementById("select_metric_combo");
        var obj2 = document.getElementById("select_category_combo");
        //console.log(obj1.options,obj2.options[b]);
        if(!obj1.options[a].selected){
            obj1.options[a].selected = true;
            changeMetric(obj1);
        }
        if(!obj2.options[b].selected){
            obj2.options[b].selected = true;
            changeCategory(obj2);
        }        
    }
</script>

<script src="<?php echo base_url('static/js/d3/d3.min.js')?>" charset="utf-8"></script>
<script>
function draw(){
    //横坐标数组
    var xdata = <?php echo json_encode($alpha_columndata['column'])?>;
    console.log(xdata);
    //纵坐标数组
    var ydata = <?php echo json_encode($alpha_columndata['data']['chao1'])?>;
    console.log(ydata);

    // //画布周边的空白
    var padding = 60;
    var width= $("#container").width();
    console.log(width);
    var height=400;
    console.log(height);
    //在 body 里添加一个 SVG 画布   
    var svg = d3.select("#container")
        .append("svg")
        .attr("width", width)
        .attr("height", height);
    //x轴的比例尺
    // var xScale = d3.scaleLinear()
    // .domain(xdata)
    // .range([0, width-padding-padding]);
    // var xScale = d3.scaleOrdinal()
    //     .domain(xdata)  
    //     .range(['black', '#ccc', '#ccc']);
    var xScale = d3.scaleLinear()
        // .domain([1,xdata.length])
        .range([0,width-padding-padding]);
    xScale.domain(d3.extent(xdata, function(d,i){console.log(d,i); return i;}));
    //y轴的比例尺
    var yScale = d3.scaleLinear()
        .domain([0,d3.max(ydata)])
        .range([height-padding-padding, 0]);

    //定义坐标轴
    var xAxis=d3.axisBottom().scale(xScale);
    var yAxis=d3.axisLeft().scale(yScale);

   //添加x轴
    var gAxis = svg.append("g")  
        .attr("class","axis")  
        .attr("transform","translate("+padding+","+(height-padding)+")"); 
    xAxis(gAxis);
    //添加y轴
    var gAxis = svg.append("g")
      .attr("class","axis")
      .attr("transform","translate("+padding+","+padding+")")
    yAxis(gAxis);

    //定义柱形图的 scale
    var xScalerect = d3.scaleBand()  
                        .domain(xdata.length)  
                        .rangeRound([0,500],0.05);  
    console.log(xScalerect(10));               
    var yScalerect = d3.scaleLinear()  
                        .domain([0,d3.max(ydata)])  
                        .range([0,500]);
    console.log(xScalerect(500));
    // //矩形之间的空白
    var rectPadding = xScale(1)/3;
    var x = d3.scaleBand().rangeRound([0, width]);
console.log(rectPadding);
    //添加矩形元素
    var rects = svg.selectAll(".MyRect")
        .data(ydata)
        .enter()
        .append("rect")
        .attr("class","MyRect")
        .attr("transform","translate(" + (rectPadding/2+padding) + "," + padding + ")")
        .attr("x", function(d,i){  
                return 30 + xScalerect(i); 
                console.log(xScalerect(i)); 
           } )  
           .attr("y",function(d,i){  
                return 50 + 500 - yScalerect(d) ;  
           })  
           .attr("width", function(d,i){  
                return xScalerect.rangeRound();  
           })  
           .attr("height",yScalerect)  
           .attr("fill","red");

    var new_h1 = d3.select("#container").append("h3").text("Append new h1");
}
</script>
<!-- <script type="text/javascript">
    function draw(){
        //画布大小
        var width = 600;  
        var height = 600;  
        var dataset = [];  
        var num = 15;  //数组的数量  
          
        for(var i = 0; i < num ; i++){  
            var tempnum = Math.floor( Math.random() * 50 );   // 返回 0~49 整数  
            dataset.push(tempnum);  
        }  
          
        var svg = d3.select("#container").append("svg")  
                                .attr("width",width)  
                                .attr("height",height);  
          
        var xAxisScale = d3.scale.ordinal()  
                        .domain(d3.range(dataset.length))  
                        .rangeRoundBands([0,500]);  
                              
        var yAxisScale = d3.scale.linear()  
                        .domain([0,d3.max(dataset)])  
                        .range([500,0]);  
                              
        var xAxis = d3.svg.axis()  
                        .scale(xAxisScale)  
                        .orient("bottom");  
          
        var yAxis = d3.svg.axis()  
                        .scale(yAxisScale)  
                        .orient("left");  
  
        var xScale = d3.scale.ordinal()  
                        .domain(d3.range(dataset.length))  
                        .rangeRoundBands([0,500],0.05);  
                              
        var yScale = d3.scale.linear()  
                        .domain([0,d3.max(dataset)])  
                        .range([0,500]);  
          
        svg.selectAll("rect")  
           .data(dataset)  
           .enter()  
           .append("rect")  
           .attr("x", function(d,i){  
                return 30 + xScale(i);  
           } )  
           .attr("y",function(d,i){  
                return 50 + 500 - yScale(d) ;  
           })  
           .attr("width", function(d,i){  
                return xScale.rangeBand();  
           })  
           .attr("height",yScale)  
           .attr("fill","red");  
             
        svg.selectAll("text")  
            .data(dataset)  
            .enter().append("text")  
            .attr("x", function(d,i){  
                return 30 + xScale(i);  
           } )  
           .attr("y",function(d,i){  
                return 50 + 500 - yScale(d) ;  
           })  
            .attr("dx", function(d,i){  
                return xScale.rangeBand()/3;  
           })  
            .attr("dy", 15)  
            .attr("text-anchor", "begin")  
            .attr("font-size", 14)  
            .attr("fill","white")  
            .text(function(d,i){  
                return d;  
            });  
             
        svg.append("g")  
            .attr("class","axis")  
            .attr("transform","translate(30,550)")  
            .call(xAxis);  
              
        svg.append("g")  
            .attr("class","axis")  
            .attr("transform","translate(30,50)")  
            .call(yAxis); 
    }
</script> -->