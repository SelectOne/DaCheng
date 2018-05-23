<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <legend>充值统计图</legend>
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                {{--<form action="" method="get">--}}
                <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                    <div class="layui-inline">
                        <label class="layui-form-label">日期时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name='time' class="layui-input"  id="time" placeholder="登录日期" value="{{ old('time') }}">
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
    </fieldset>
    <br /><br /><br /><br /><br />
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>分析数据</legend>
    </fieldset>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">累计充值总金额:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid "><span style="font-weight: bold;color: black;">{{$num1}}</span>元</div>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">充值总人数:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid " style="font-weight: bold;color: black;">{{$num2}}</div>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">二次充值人数:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid " style="font-weight: bold;color: black;">{{$num3}}</div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">一次性充值最高金额:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid " ><span style="font-weight: bold;color: black;">{{$num4->amount}}</span>元</div>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">充值最高金额日期:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid " style="font-weight: bold;color: black;">{{$num4->created_at}}</div>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">使用最多的充值渠道:</label>
            <div class="layui-input-inline">
                <div class="layui-form-mid " style="font-weight: bold;color: black;">{{$num5}}</div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="">注册人均充值:</label>
        <div class="layui-input-inline">
            <div class="layui-form-mid "><span style="font-weight: bold;color: black;">{{$num6}}</span>元</div>
        </div>
    </div>
    <style>
        .layui-form-label {
            width: 161px;
        }
    </style>

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
            console.log(time);
            $.getJSON('{{url("admin/api/amount")}}',{'time':time}, function (data) {
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
        $.getJSON('{{url("admin/api/amount")}}',{'time':time}, function (data) {
            // console.log(data);
            var date = data.date, value = data.value;
            // console.log(value);
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'spline',
                },
                title: {
                   text: "充值金额(元)",
                    left: -10
                },
                // subtitle: {
                //     align: "left",
                //     text: '总充值金额:' + total
                // },
                xAxis: {
                    /*title: {
                        text: AxisNames[0]
                    },*/
                    categories: date
                },
                yAxis: {
                    title: {
                        text: '充值金额'
                    }
                },
                tooltip: {
                    animation: true,
                    valueSuffix: '元'
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
                    name: '充值金额',
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