<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <legend>活跃玩家统计图</legend>
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                {{--<form action="" method="get">--}}
                <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                    <div class="layui-inline">
                        <label class="layui-form-label">日期时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name='time' class="layui-input"  id="time" placeholder="日期时间" value="{{ old('time') }}">
                        </div>
                    </div>

                    <div class="demoTable layui-inline">
                        <button class="layui-btn layui-btn-normal" onclick="fun()">查询</button>
                    </div>
                </blockquote>
                {{--</form>--}}
            </div>
        </div>
        <div id="container" style="min-width:400px;height:400px"></div>
        <div class="message"></div>
        <div id="container1" style="min-width:400px;height:400px"></div>
    </fieldset>
    <br /><br /><br /><br />



    <script src="{{ asset("js/jquery-2.1.0.js") }}"></script>
    <script src="{{ asset("highcharts/highcharts.js") }}"></script>
    <script src="{{ asset("highcharts/modules/exporting.js") }}"></script>
    <script src="{{ asset("highcharts/modules/data.js") }}"></script>
    <script src="{{ asset("highcharts/modules/series-label.js") }}"></script>
    <script src="{{ asset("highcharts/modules/oldie.js") }}"></script>
    <script src="{{ asset("highcharts/themes/dark-unica.js") }}"></script>
    <script type="text/javascript" src="http://cdn.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
    <script>

        var time = "";

        function fun() {
            time = $("#time").val();
            // console.log(time);
            $.getJSON('{{url("admin/lively1")}}',{'time':time}, function (data) {
                // console.log(data.date);
                var date = data.date, value = data.value;
                // console.log(total);
                chart.series[0].update({
                    data: value
                })
                chart.xAxis[0].update({
                    categories: date
                })
            })
        }

        var chart;
        $.getJSON('{{url("admin/lively1")}}',{'time':time}, function (data) {
            /*console.log(data);
            console.log(data.date);
            console.log(data.value);*/
            var date = data.date, value = data.value;

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'spline',
                },
                title: {
                    text: "当天在线时长大于1小时的玩家",
                    left: -10
                },
                /*subtitle: {
                    align: "top",
                    text: '注册总人数:' + total + "人"
                },*/
                xAxis: {
                    /*title: {
                        text: AxisNames[0]
                    },*/
                    categories: date
                },
                yAxis: {
                    title: {
                        text: '活跃玩家数'
                    }
                },
                tooltip: {
                    animation: true,
                    valueSuffix: '人'
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'top',
                    y: 20,
                    floating: true,
                    borderWidth: 0
                },
                plotOptions: {
                    series: {
                        animation: {
                            duration: 2000
                        },
                        allowPointSelect: true,
                        cursor: 'pointer',
                        events: {
                            click: function (e) {
                                console.log(e.point.y);
                            }
                        }
                        // pointStart: 2010
                    }
                },
                series: [{
                    name: '活跃玩家数',
                    data: value
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });
        });
    </script>

    <script>
        var chart1 = Highcharts.chart('container1', {
            title: {
                text: '当月玩家游戏时长数'
            },
            tooltip: {
                headerFormat: '{series.name}<br>',
                pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                        formatter: function() {
                            if (this.percentage > 0)
                                return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %'; // 这里进行判断
                        }
                    },
                    showInLegend: true // 设置饼图是否在图例中显示
                }
            },
            series: [{
                type: 'pie',
                name: '游戏时长占比',
                data: [
                    ['1小时以下',{{$data[0]}}],
                    ['1小时到40小时',{{$data[1]}}],
                    ['40小时以上',{{$data[2]}}],
                ]
            }]
        });
    </script>

    <script>
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            //日期范围
            laydate.render({
                elem: '#time'
                ,range: '--'
            });
        });
    </script>
@endsection