<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','金币统计')
<!-- 内容 -->
@section('content')
        <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
            <div id="container" style="min-width:400px;height:400px"></div>
            <div style="background: #373738; color: white;height: 30px;font-size: 17px;text-align: center ">
                总金币数:{{$sum}} &nbsp;&nbsp;&nbsp; 总人数: {{$count}}人</div>


        </fieldset>
        <script src="{{ asset("js/jquery-2.1.0.js") }}"></script>
        <script src="{{ asset("highcharts/highcharts.js") }}"></script>
        <script src="{{ asset("highcharts/modules/exporting.js") }}"></script>
        <script src="{{ asset("highcharts/modules/data.js") }}"></script>
        <script src="{{ asset("highcharts/modules/series-label.js") }}"></script>
        <script src="{{ asset("highcharts/modules/oldie.js") }}"></script>
        <script src="{{ asset("highcharts/themes/dark-unica.js") }}"></script>
        <script type="text/javascript" src="http://cdn.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

        <script>
            var chart = Highcharts.chart('container', {
                title: {
                    text: '游戏币分布图'
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
                    name: '金币区间占比',
                    data: [
                        ['一万以下({{$data2[0]}}人)',  {{$data[0]}}],
                        ['1万至10万({{$data2[1]}}人)',       {{$data[1]}}],
                        ['10万至50万({{$data2[2]}}人)',    {{$data[2]}}],
                        ['50万至100万({{$data2[3]}}人)',     {{$data[3]}}],
                        ['100万至500万({{$data2[4]}}人)',   {{$data[4]}}],
                        ['500万至1000万({{$data2[5]}}人)',  {{$data[5]}}],
                        ['1000万至3000万({{$data2[6]}}人)',   {{$data[6]}}],
                        ['3000万以上({{$data2[7]}}人)',   {{$data[7]}}]
                    ]
                }]
            });
        </script>

@endsection
