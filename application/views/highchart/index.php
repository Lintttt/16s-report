<script src="<?php echo base_url('static/js/jquery-ui.js')?>"></script>
<?php $this->load->view("public/topbar"); ?>
<div id="wrapper">
<?php $this->load->view("public/sidebar"); ?>

    <div id="page-wrapper">
        <!--BEGIN TITLE & BREADCRUMB PAGE-->
        <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
            <div class="page-header pull-left">
                <div class="page-title">
                    HighCharts
                </div>
            </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="dashboard.html">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Charts</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active">Charts</li>
                </ol>
                <div class="clearfix">
                </div>
        </div>
        <!--END TITLE & BREADCRUMB PAGE-->
            <div class="page-content">
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
                                <div class="col-lg-6">
                                    <div class="portlet box">
                                        <div class="portlet-header">
                                            <div class="caption">Line Chart</div>
                                                <div class="tools">
                                                    <i class="fa fa-chevron-up"></i>
                                                    <i data-toggle="modal" data-target="#modal-config" class="fa fa-cog"></i>
                                                    <i class="fa fa-refresh"></i>
                                                    <i class="fa fa-times"></i>
                                                </div>
                                            </div>
                                        <div class="portlet-body">
                                            <div id="line-chart" style="width: 100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="portlet box">
                                        <div class="portlet-header">
                                            <div class="caption">Line Chart</div>
                                                <div class="tools">
                                                    <i class="fa fa-chevron-up"></i>
                                                    <i data-toggle="modal" data-target="#modal-config" class="fa fa-cog"></i>
                                                    <i class="fa fa-refresh"></i>
                                                    <i class="fa fa-times"></i>
                                                </div>
                                            </div>
                                        <div class="portlet-body">
                                            <div id="hc_container2" style="width: 100%; height:300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="portlet box">
                                        <div class="portlet-header">
                                            <div class="caption">Responsed Chart</div>
                                                <div class="tools">
                                                    <i class="fa fa-chevron-up"></i>
                                                    <i data-toggle="modal" data-target="#modal-config" class="fa fa-cog"></i>
                                                    <i class="fa fa-refresh"></i>
                                                    <i class="fa fa-times"></i>
                                                </div>
                                            </div>
                                        <div class="portlet-body">
                                            <div id="container3" style="width: 100%; height:300px;" onresize="resize()"></div>
                                            <button id="large">设置宽度为 800px</button>
                                            <button id="small">设置宽度为 400px</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
             </div>
        <!--END CONTENT-->
    </div>
</div>

<script type="text/javascript">
$(function() {
    // 全局配置，针对页面上所有图表有效
    Highcharts.setOptions({
        chart: {
            backgroundColor: {
                linearGradient: [0, 0, 500, 500],
                stops: [
                    [0, 'rgb(255, 255, 255)'],
                    [1, 'rgb(240, 240, 255)']
                ]
            },
            borderWidth: 2,
            plotBackgroundColor: 'rgba(255, 255, 255, .9)',
            plotShadow: true,
            plotBorderWidth: 1
        }
    });
    var chart1 = new Highcharts.Chart({
        chart: {
            renderTo: 'line-chart',
        },
        xAxis: {
            type: 'datetime'
        },
        series: [{
            data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
            pointStart: Date.UTC(2010, 0, 1),
            pointInterval: 3600 * 1000 // one hour
        }]
    });
    var chart2 = new Highcharts.Chart({
        chart: {
            renderTo: 'hc_container2',
            type: 'column'
        },
        xAxis: {
            type: 'datetime'
        },
        series: [{
            data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
            pointStart: Date.UTC(2010, 0, 1),
            pointInterval: 3600 * 1000 // one hour
        }]
    });

    var chart3 = new Highcharts.Chart({
        chart: {
            renderTo: 'container3',
            type: 'column'
        },
        title: {
            text: '响应式图表'
        },
        subtitle: {
            text: '响应式切换位置和样式'
        },
        legend: {
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical'
        },
        xAxis: {
            categories: ['苹果', '橘子', '香蕉'],
            labels: {
                x: -10
            }
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: '金额'
            }
        },
        series: [{
            name: '圣诞平安夜',
            data: [1, 4, 3]
        }, {
            name: '圣诞节晚餐前一天',
            data: [6, 4, 2]
        }, {
            name: '圣诞节晚餐后一天',
            data: [8, 4, 3]
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },
                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -5
                        },
                        title: {
                            text: null
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        }
    });

    $('#small').click(function () {
        chart3.setSize(400, 300);
    });
    $('#large').click(function () {
        var chart3_width = $("#container3").width();
        chart3.setSize(chart3_width, 300);
    });

    $("#menu-toggle").click(function(){
        setTimeout('funA()', 280); 
        
    });
});
    function resize(){
        var chart3_width = $("#container3").width();
        alert(chart3_width);
        $("#container3").setSize(chart3_width, 300);
    }
 function funA(){
    var chart = $('#line-chart').highcharts();
    chart.reflow();
    var chart = $('#hc_container2').highcharts();
    chart.reflow();
 }
</script>