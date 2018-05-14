<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','管理员列表')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                <div class="layui-btn-group demoTable" style="float: right; margin-right: 6px">
                    <a href="{{ route('admin.create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>
                </div>
            </div>

            <table id="demo" lay-filter="test"></table>
            <script type="text/html" id="barDemo">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            </script>
        </div>
    </fieldset>

    <script type="text/html" id="tpl">

        @{{#  if(d.rolesID){ }}
        <span style="color: orangered;">
            @{{d.rolesID}}
        </span>
        @{{#  } else { }}
        正常
        @{{#  } }}
    </script>

    <script>
        layui.use(['table', 'form'], function() {
            var table = layui.table,
                form  = layui.form;

            table.render({
                elem: '#demo'
                , url: '{{ route("admin.getData") }}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'admin_id', title: '序号', sort: true}
                    , {field: 'admin_name', title: '管理员名称'}
                    , {field: 'rolesID', title: '角色名称', templet: "#tpl"}
                    , {field: 'created_time', title: '创建时间'}
                    , {field: 'updated_time', title: '更新时间'}
                    , {fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}
                ]]
                , id: 'testReload'
            });

            //监听工具条
            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除此条数据?', function(index){
                        var token = "{{csrf_token()}}";
                        $.ajax({
                            type: 'DELETE',
                            dataType: 'json',
                            data: {'_token':token},
                            url: 'admin/' + data.admin_id,
                            success: function (data) {
                                if (data.code == 1) {
                                    layer.alert(data.msg, {icon: 1});
                                    obj.del();
                                } else {
                                    layer.alert(data.msg, {icon: 2});
                                }
                            }
                        })
                    });
                } else if(obj.event === 'edit'){
                    location.href= 'admin/'+ data.admin_id+'/edit';
                }
            });
        });
    </script>
@endsection

