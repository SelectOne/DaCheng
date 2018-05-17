<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','库存统计')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <table id="demo"  class="layui-table"  lay-filter="test"></table>
    </fieldset>

    <script type="text/html" id="sexTpl">
        @verbatim
        {{#  if(d.sex){ }}
        男
        {{#  } else { }}
        <span style="color: #F581B1;">女</span>
        {{#  } }}
        @endverbatim
    </script>

    <script type="text/html" id="tpl1">
        @verbatim
        {{#  if(d.mid != '0'){ }}
        <a class="layui-table-link">{{ d.mid }}</a>
        {{#  } else { }}
        {{ d.mid }}
        {{#  } }}
        @endverbatim
    </script>

    <script type="text/html" id="tpl2">
        @verbatim
        {{#  if(d.status == 1){ }}
        已完成
        {{#  } else if (d.status == 2) { }}
        <span style="color: orangered">未支付</span>
        {{#  } else if (d.status == 0) { }}
        <span style="color: red;">已取消</span>
        {{#  } }}
        @endverbatim
    </script>

    <script type="text/html" id="tpl3">
        @verbatim
        {{#  if(d.type == 1){ }}
        支付宝
        {{#  } else if (d.status == 2) { }}
        微信
        {{#  } else if (d.status == 3) { }}
        银行卡
        {{#  } else if (d.status == 4) { }}
        其它
        {{#  } else if (d.status == 0) { }}
        实卡充值
        {{#  } }}
        @endverbatim
    </script>

    <script>
        layui.use(['element','form','layer','laydate'], function(){
            var $ = layui.$;
            var element = layui.element;
            var form = layui.form;
            var laydate = layui.laydate;
            form.render();

            //执行一个laydate实例
            //日期范围
            laydate.render({
                elem: '#created_time'
                ,range: '--'
            });

        });

        layui.use('table', function() {
            var table = layui.table;

            table.render({
                elem: '#demo'
                , url: '{{route("cardinfo.getData")}}' //数据接口
                , where: {
                    {{--id: $('#id').val()--}}
                    {{--,ip: "{{ $input['ip']??"" }}"--}}
                    {{--,machine_ip: "{{ $input['machine_ip']??"" }}"--}}
                }
                , method: 'get'
                , width: 1640
                , height: 501
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'card_name', title: '会员卡',}
                    , {field: 'card_num', title: '库存', sort: true}
                    , {field: 'used', title: '已充值',sort: true}
                    , {field: 'not_used', title: '未充值'}
                    , {field: 'expire', title: '已过期'}
                    , {field: 'total_price', title: '总金额', sort: true}
                    , {field: 'total_given', title: '总金币', sort: true}
                    , /*{fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}*/
                ]]
            });

            //排序
            table.on('sort(test)', function(obj){
                table.reload('demo', {
                    initSort: obj
                    ,where: {
                        field: obj.field //排序字段
                        ,order: obj.type //排序方式
                    }
                });
            });
        });

    </script>

    <script>
        layui.use('table', function(){
            var table = layui.table;
            //监听表格复选框选择
            /*table.on('checkbox(test)', function(obj){
                console.log("监听表格复选框选择:");
                console.log(obj)
            });*/

            var $ = layui.$, active = {
                reload:function() {
                    table.reload('demo', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            id: $("input[name='id']").val()
                            ,search_type: $("[name='search_type']").val()
                            ,type: $("[name='type']").val()
                            ,status: $("[name='status']").val()
                            ,created_time: $("input[name='created_time']").val()
                        }
                    });
                },Excel:function() {
                    table.reload('demo', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            Excel: $("input[name='Excel']").val()
                        }
                    });
                }
            };

            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>
@endsection
