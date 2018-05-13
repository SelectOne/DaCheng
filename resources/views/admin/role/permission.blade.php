<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','权限列表')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
        <div class="layui-field-box">
            <div class="layui-col-xs12">
                <div class="layui-btn-group demoTable" style="float: right; margin-right: 6px">
                    <button class="layui-btn layui-btn-normal" data-type="add">新增</button>
                </div>
            </div>

            <table id="demo" lay-filter="test"></table>
            <script type="text/html" id="barDemo">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            </script>
        </div>
    </fieldset>

    <script>
        layui.use(['table', 'form'], function() {
            var table = layui.table,
                form  = layui.form;

            table.render({
                elem: '#demo'
                , url: '{{url("admin/permission/getData")}}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: '序号', sort: true}
                    , {field: 'name', title: '权限名称'}
                    , {field: 'display_name', title: '显示名称'}
                    , {field: 'description', title: '描述'}
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
                        $.get("{{url('admin/permission/destroy')}}", {'id':data.id}, function (data) {
                            layer.msg(data);
                        })
                        obj.del();
                        layer.close(index);
                    });
                } else if(obj.event === 'edit'){
                    layer.open({
                        id: 1,
                        type: 1,
                        title: ['修改权限', 'font-size:18px;'],
                        skin: 'layui-layer-rim',
                        area: ['500px', 'auto'],
                        offset: '100px',
                        content: "<form class=\"layui-form\" action=\"{{url('admin/permission/update')}}\"  method=\"post\" id=\"form2\">\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">权限名称:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"name\" class=\"layui-input\" style=\"width: 300px\" disabled>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">显示名称:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"display_name\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">描述:</label>\n" +
                        "            <div class=\"layui-input-block\" >\n" +
                        "                <input type=\"text\" name=\"description\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "     <input type=\"hidden\" name=\"_token\" value=\"{{csrf_token()}}\">" +
                        "     <input type=\"hidden\" name=\"id\" value=\"\">" +
                        "    </form>",
                        btn: ['确定', '取消'],
                        success: function(layero, index){
                            form.render();
                            $("[name='name']").val(data.name);
                            $("[name='display_name']").val(data.display_name);
                            $("[name='description']").val(data.description);
                            $("[name='id']").val(data.id);
                        },
                        btn1: function (index, layero) {
                            $('#form2').submit();
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            layer.close(index);
                        }
                    });
                }
            });
        });
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
                    table.reload('testReload', {
                        // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                        where: {
                            limit_ip: $("input[name='limit_ip']").val()
                            ,type: type
                        }
                    });
                }
                ,add: function () {
                    console.log("sad");
                    layer.open({
                        id: 1,
                        type: 1,
                        title: ['新增权限', 'font-size:18px;'],
                        skin: 'layui-layer-rim',
                        area: ['500px', 'auto'],
                        offset: '100px',
                        content: "<form class=\"layui-form\" action=\"{{url('admin/permission/store')}}\"  method=\"post\" id=\"form\">\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">权限名称:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"name\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">显示名称:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"display_name\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">描述:</label>\n" +
                        "            <div class=\"layui-input-block\" >\n" +
                        "                <input type=\"text\" name=\"description\" id=\"description\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "     <input type=\"hidden\" name=\"_token\" value=\"{{csrf_token()}}\">" +
                        "    </form>",
                        btn: ['确定', '取消'],
                        btn1: function (index, layero) {
                            $('#form').submit();
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            layer.close(index);
                        },success: function(layero, index){
                            form.render();
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
