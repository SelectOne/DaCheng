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
                        <label class="layui-form-label">操作账号</label>
                        <div class="layui-input-inline">
                            <select name="admin_id" lay-verify="">
                                <option value="">请选择</option>
                            @foreach ($admin as  $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">操作类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-verify="">
                                <option value="">请选择</option>
                                <option value="1">安全日志</option>
                                <option value="2">操作日志</option>
                                <option value="3">登录日志</option>
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
            form.render();

            table.render({
                elem: '#demo'
                , url: '{{ route("log.getData") }}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: '序号'}
                    , {field: 'created_time', title: '操作时间'}
                    , {field: 'admin_name', title: '操作账号'}
                    , {field: 'title', title: '操作'}
                    , {field: 'ip', title: '操作IP'}
                ]]
            });

            //排序
            table.on('sort(test)', function (obj) {
                console.log(obj.field); //当前排序的字段名
                console.log(obj.type); //当前排序类型：desc（降序）、asc（升序）、null（空对象，默认排序）
                console.log(this); //当前排序的 th 对象
                table.reload('demo', {
                    initSort: obj
                    , where: {
                        field: obj.field //排序字段
                        , order: obj.type //排序方式
                    }
                });
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
                            admin_id: $("[name='admin_id']").val()
                            , type: $("[name='type']").val()
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