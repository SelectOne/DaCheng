<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','用户列表')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                    <div class="layui-inline">
                        <label class="layui-form-label">游戏房间</label>
                        <div class="layui-input-inline">
                            <select name="room_id" lay-verify="">
                                <option value="">请选择</option>
                                @foreach ($rooms as $room)
                                <option value="{{$room->id}}">{{$room->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="demoTable layui-inline">
                        <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
                    </div>
                </blockquote>
            </div>
            <table id="demo" lay-filter="test"></table>
        </div>
    </fieldset>
    <script>
        layui.use(['table', 'form', 'laydate'], function() {
            var table = layui.table,
                form = layui.form,
                laydate = layui.laydate;

            table.render({
                elem: '#demo'
                , url: '{{url("admin/mInRoom")}}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: '用户ID'}
                    , {field: 'realname', title: '用户名'}
                    , {field: 'nickname', title: '限制登录'}
                    , {field: 'game_id', title: '游戏ID'}
                    , {field: 'room_name', title: '所在房间'}
                    , {field: 'ip', title: '进入IP'}
                    , {field: 'join_time', title: '进入时间'}
                ]]
            });
        })
    </script>
    <script>
        layui.use(['element','form','layer'], function(){
            var $ = layui.$;
            var element = layui.element;
            var form = layui.form;
            form.render();

        });

        layui.use(['form','layer','table','laydate'], function(){
            var laydate = layui.laydate;
            var form = layui.form;
            form.render();
            var table = layui.table,type = 0;
            form.on('switch(switchTest)', function(data){
                type = this.checked ? '1' : '0';
            });

            var $ = layui.$, active = {
                reload:function() {
                    table.reload('demo', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            limit_ip: $("input[name='limit_ip']").val()
                            ,type: type
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
