<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                    <div class="layui-inline">
                        <label class="layui-form-label">日期时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name='time' class="layui-input" id="time" placeholder="登录日期" value="{{ old('time') }}">
                        </div>
                    </div>

                    <div class="demoTable layui-inline">
                        <button class="layui-btn layui-btn-normal" data-type="reload">查询</button>
                    </div>
                </blockquote>
            </div>
        </div>
        <div id="container" style="min-width:400px;height:400px"></div>
        <div class="message"></div>
    </fieldset>
    <script src="{{ asset("js/jquery-2.1.0.js") }}"></script>
    <script src="{{ asset("highcharts/highcharts.js") }}"></script>
    <script src="{{ asset("highcharts/modules/exporting.js") }}"></script>
    <script src="{{ asset("highcharts/modules/data.js") }}"></script>
    <script src="{{ asset("highcharts/modules/series-label.js") }}"></script>
    <script src="{{ asset("highcharts/modules/oldie.js") }}"></script>
    <script src="{{ asset("highcharts-zh_CN.js") }}"></script>
    <script src="{{ asset("highcharts/themes/dark-unica.js") }}"></script>
    <script>
        var chart = null;
        // 获取 CSV 数据并初始化图表
        $.getJSON('https://data.jianshukeji.com/jsonp?filename=csv/analytics.csv&callback=?', function (csv) {
            chart = Highcharts.chart('container', {
                data: {
                    csv: csv
                },
                title: {
                    text: '某网站日常访问量'
                },
                subtitle: {
                    text: '数据来源: Google Analytics'
                },
                xAxis: {
                    tickInterval: 7 * 24 * 3600 * 1000, // 坐标轴刻度间隔为一星期
                    tickWidth: 0,
                    gridLineWidth: 1,
                    labels: {
                        align: 'left',
                        x: 3,
                        y: -3
                    },
                    // 时间格式化字符
                    // 默认会根据当前的刻度间隔取对应的值，即当刻度间隔为一周时，取 week 值
                    dateTimeLabelFormats: {
                        week: '%Y-%m-%d'
                    }
                },
                yAxis: [{ // 第一个 Y 轴，放置在左边（默认在坐标）
                    title: {
                        text: null
                    },
                    labels: {
                        align: 'left',
                        x: 3,
                        y: 16,
                        format: '{value:.,0f}'
                    },
                    showFirstLabel: false
                }, {    // 第二个坐标轴，放置在右边
                    linkedTo: 0,
                    gridLineWidth: 0,
                    opposite: true,  // 通过此参数设置坐标轴显示在对立面
                    title: {
                        text: null
                    },
                    labels: {
                        align: 'right',
                        x: -3,
                        y: 16,
                        format: '{value:.,0f}'
                    },
                    showFirstLabel: false
                }],
                legend: {
                    align: 'left',
                    verticalAlign: 'top',
                    y: 20,
                    floating: true,
                    borderWidth: 0
                },
                tooltip: {
                    shared: true,
                    crosshairs: true,
                    // 时间格式化字符
                    // 默认会根据当前的数据点间隔取对应的值
                    // 当前图表中数据点间隔为 1天，所以配置 day 值即可
                    dateTimeLabelFormats: {
                        day: '%Y-%m-%d'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                // 数据点点击事件
                                // 其中 e 变量为事件对象，this 为当前数据点对象
                                click: function (e) {
                                    $('.message').html( Highcharts.dateFormat('%Y-%m-%d', this.x) + ':<br/>  访问量：' +this.y );
                                }
                            }
                        },
                        marker: {
                            lineWidth: 1
                        }
                    }
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