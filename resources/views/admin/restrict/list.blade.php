<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','用户列表')
<!-- 内容 -->
@section('content')
    <div class="layui-form">
        <table id="demo"  class="layui-table"  lay-filter="test"></table>
    </div>

    <script type="text/html" id="tpl1">
        <a href="{{url("admin/member/index?id=")}}@{{ d.mid }}" class="layui-table-link">@{{ d.mid }}</a>
    </script>

    <script>
        layui.use(['table', 'form'], function() {
            var table = layui.table,
                form = layui.form;
            form.render();

            table.render({
                elem: '#demo'
                , url: '{{url("admin/getMember")}}' //数据接口
                , where: {
                    ip: "{{ $input['ip']??"" }}"
                    ,machine_ip: "{{ $input['machine_ip']??"" }}"
                }
                , method: 'get'
                , width: 1640
                , height: 501
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'mid', title: '用户ID', templet: '#tpl1'}
                    , {field: 'ip', title: 'IP地址'}
                    , {field: 'machine_ip', title: '机器码'}
                    , {field: 'created_at', title: '时间'}
                ]]
            });
        });
    </script>
@endsection
