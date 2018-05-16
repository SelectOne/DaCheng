<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','订单管理')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                    <div class="layui-inline">
                        <label class="layui-form-label">基本查询</label>
                        <div class="layui-input-inline">
                            <input type="text" name="id" id="id"  class="layui-input" value="{{ old("id") }}" placeholder="用户ID/游戏ID/订单号码">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="search_type" lay-verify="">
                                <option value="1">按用户ID</option>
                                <option value="2">按游戏ID</option>
                                <option value="3">按订单号码</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">充值类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-verify="">
                                <option value="">请选择</option>
                                <option value="0">实卡充值</option>
                                <option value="1">支付宝</option>
                                <option value="2">微信</option>
                                <option value="3">银行卡</option>
                                <option value="4">其它</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">订单状态</label>
                        <div class="layui-input-inline">
                            <select name="status" lay-verify="">
                                <option value="">请选择</option>
                                <option value="0">已取消</option>
                                <option value="1">已完成</option>
                                <option value="2">未支付</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">订单日期</label>
                        <div class="layui-input-inline">
                            <input type="text" name='created_time' class="layui-input" id="created_time" placeholder="订单日期" value="{{ old('created_time') }}">
                        </div>
                    </div>
                    <input type="hidden" name="Excel" value="1">
                    <div class="demoTable layui-inline">
                        <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="layui-btn-group demoTable">
            <button class="layui-btn layui-btn-normal" data-type="Excel">导出Excel表格</button>
        </div>
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
                , url: '{{url("admin/order/getData")}}' //数据接口
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
                    , {field: 'created_time', title: '订单日期', width:200, unresize: true, sort: true}
                    , {field: 'sn', title: '订单号码', unresize: true, sort: true}
                    , {field: 'type', title: '充值类型', unresize: true, sort: true, templet: '#tpl3'}
                    , {field: 'mid', title: '用户账号', templet: '#tpl1'}
                    , {field: 'game_id', title: '游戏ID'}
                    , {field: 'amount', title: '订单金额', sort: true}
                    , {field: 'given', title: '赠送金额', sort: true}
                    , {field: 'paid', title: '实付金额', sort: true}
                    , {field: 'status', title: '订单状态', unresize: true, templet: '#tpl2'}
                    , {field: 'address', title: '订单地址'}
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
