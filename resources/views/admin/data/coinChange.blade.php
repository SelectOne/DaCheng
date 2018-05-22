<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>玩家账变记录</legend>
    </fieldset>
    <div class="layui-field-box">
        <div class="layui-col-xs12">
            <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                <div class="layui-inline">
                    <label class="layui-form-label">玩家ID</label>
                    <div class="layui-input-inline">
                        <input type="text" name="id" id="id"  class="layui-input" value="{{ old("id") }}" placeholder="ID">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">账变类型</label>
                    <div class="layui-input-inline">
                        <select name="type" lay-verify="">
                            <option value="">请选择</option>
                            <option value="1">游戏</option>
                            <option value="2">在线充值</option>
                            <option value="3">实卡充值</option>
                            <option value="4">注册赠送</option>
                            <option value="5">后台赠送</option>
                            <option value="6">任务赠送</option>
                            <option value="7">签到赠送</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">时间日期</label>
                    <div class="layui-input-inline">
                        <input type="text" name="created_at" class="layui-input" id="created_at" placeholder="时间日期" style="width: 190px">
                    </div>
                </div>

                <div class="demoTable layui-inline">
                    <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
                </div>
            </blockquote>
        </div>
        <table id="demo" lay-filter="test"></table>
    </div>

    <script type="text/html" id="tpl1">
        @verbatim
            {{#  if(d.end_coin > d.start_coin){ }}
            <span style="color: green">+</span>{{ d.change_coin }}
            {{#  } else { }}
            <span style="color: red">-</span>{{d.change_coin }}
            {{#  } }}
        @endverbatim
    </script>

    <script>
        layui.use(['table', 'form', 'laydate'], function() {
            var table = layui.table,
                form = layui.form,
                laydate = layui.laydate;

            laydate.render({
                elem: '#created_at',
                range: '--'
            });

            form.render();

            table.render({
                elem: '#demo'
                , url: '{{ url("admin/coin") }}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {field: 'id', title: '序号'}
                    , {field: 'mid', title: '玩家ID'}
                    , {field: 'type', title: '账变类型'}
                    , {field: 'start_coin', title: '起始金币'}
                    , {field: 'change_coin', title: '变化金币', templet: "#tpl1"}
                    , {field: 'end_coin', title: '结束金币'}
                    , {field: 'created_at', title: '时间'}
                ]]
            });
        });
    </script>

    <script>
        layui.use(['form','layer','table','laydate'], function() {
            var laydate = layui.laydate;
            var form = layui.form;
            form.render();
            var table = layui.table;

            var $ = layui.$, active = {
                reload: function () {
                    table.reload('demo', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            id: $("[name='id']").val()
                            , type: $("[name='type']").val()
                            , created_at: $("[name='created_at']").val()
                        }
                    });
                }
            }

            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        })
    </script>
@endsection