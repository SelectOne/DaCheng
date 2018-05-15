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
                        <label class="layui-form-label">IP/机器码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="limit_ip" id="limit_ip"  class="layui-input" value="{{ old("limit_ip") }}" placeholder="ID">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">模糊查询</label>
                        <div class="layui-input-inline">
                            <input type="checkbox" name="type" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF" >
                        </div>
                    </div>
                    <div class="demoTable layui-inline">
                        <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
                    </div>

                    <div class="layui-btn-group demoTable" style="float: right">
                        <button class="layui-btn layui-btn-normal" data-type="add">新增</button>
                    </div>
                </blockquote>
            </div>

            <table id="demo" lay-filter="test"></table>
        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
        </div>
    </fieldset>
    <script type="text/html" id="Tpl1">
        @verbatim
            {{#  if(d.limit_login){ }}
            {{ '禁止' }}
            {{#  } else { }}
            {{ '正常' }}
            {{#  } }}
        @endverbatim
    </script>

    <script type="text/html" id="Tpl2">
        @verbatim
            {{#  if(d.limit_regist){ }}
            {{ '禁止' }}
            {{#  } else { }}
            {{ '正常' }}
            {{#  } }}
        @endverbatim
    </script>

    <script type="text/html" id="Tpl3">
            @{{#  if(d.type){ }}
            @{{ "机器码:" }}<a href="{{url("admin/member/index?machine_ip=")}}@{{d.ip}}" class="layui-table-link">@{{ d.ip }}</a>
            @{{#  } else { }}
            @{{ "IP:" }}<a href="{{url("admin/member/index?ip=")}}@{{d.ip}}" class="layui-table-link">@{{ d.ip }}</a>
            @{{#  } }}
    </script>

    <script>
        layui.use(['table', 'form', 'laydate'], function() {
            var table = layui.table,
                form  = layui.form,
                laydate = layui.laydate;

            table.render({
                elem: '#demo'
                , url: '{{url("admin/restrict/getData")}}' //数据接口
                , width: 1640
                , height: 500
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: '序号', sort: true}
                    , {field: 'ip', title: 'IP/机器码', templet: '#Tpl3'}
                    , {field: 'limit_login', title: '限制登录', templet: '#Tpl1'}
                    , {field: 'limit_regist', title: '限制注册', templet: '#Tpl2'}
                    , {field: 'limit_time', title: '失效时间'}
                    , {field: 'create_time', title: '录入时间'}
                    , {field: 'content', title: '备注'}
                    , {fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}
                ]]
            });

            //排序
            table.on('sort(test)', function(obj){
                console.log(obj.field); //当前排序的字段名
                console.log(obj.type); //当前排序类型：desc（降序）、asc（升序）、null（空对象，默认排序）
                console.log(this); //当前排序的 th 对象
                table.reload('demo', {
                    initSort: obj
                    ,where: {
                        field: obj.field //排序字段
                        ,order: obj.type //排序方式
                    }
                });
            });

            //监听工具条
            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除此条数据?', function(index){
                        $.get("{{url('admin/restrict/destroy')}}", {'id':data.id}, function (data) {
                            layer.msg(data);
                        })
                        obj.del();
                        layer.close(index);
                    });
                } else if(obj.event === 'edit'){
                    layer.open({
                        id: 1,
                        type: 1,
                        title: ['修改限制地址', 'font-size:18px;'],
                        skin: 'layui-layer-rim',
                        area: ['500px', 'auto'],
                        offset: '100px',
                        content: "<form class=\"layui-form\" action=\"{{url('admin/restrict/update')}}\"  method=\"post\" id=\"form2\">\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">限制地址:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"ip\" class=\"layui-input\" style=\"width: 300px\" disabled>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">选项:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"checkbox\"  name=\"limit_login1\" title=\"限制登录\" lay-filter=\"cb1\">\n" +
                        "                <input type=\"checkbox\"  name=\"limit_regist1\" title=\"限制注册\" lay-filter=\"cb2\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">失效时间:</label>\n" +
                        "            <div class=\"layui-input-block\" >\n" +
                        "                <input type=\"text\" id=\"test2\" name=\"limit_time\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">警告:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <div class=\"layui-form-mid layui-word-aux\" style=\"font-weight: bold;color: black;\">失效时间不填写,则默认为永久限制!</div>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">备注:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <textarea placeholder=\"请输入内容\" class=\"layui-textarea\" name=\"content\" style=\"width: 300px\"></textarea>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">类型</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"radio\"  name=\"type\" id=\"a\" value=\"0\" title=\"IP\">\n" +
                        "                <input type=\"radio\"  name=\"type\" id=\"b\" value=\"1\" title=\"机器码\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "     <input type=\"hidden\" name=\"_token\" value=\"{{csrf_token()}}\">" +
                        "     <input type=\"hidden\" name=\"id\" id=\"id\">" +
                        "     <input type=\"hidden\" name=\"limit_login\" id=\"login\">" +
                        "     <input type=\"hidden\" name=\"limit_regist\" id=\"regist\">" +
                        "    </form>",
                        btn: ['确定', '取消'],
                       success: function(layero, index){
                            form.render();
                            laydate.render({
                                elem: '#test2',
                                type: 'datetime'
                            });

                           form.on('checkbox(cb1)', function(data){
                               console.log(data.elem.checked); //是否被选中，true或者false
                               console.log(data.value); //复选框value值，也可以通过data.elem.value得到
                               if (data.elem.checked) {
                                   $("#login").val(1)
                               }else {
                                   $("#login").val(0)
                               }
                           });

                           form.on('checkbox(cb2)', function(data){
                               if (data.elem.checked) {
                                   $("#regist").val(1)
                               }else {
                                   $("#regist").val(0)
                               }
                           });


                           $("input[name='ip']").val(data.ip);
                           if (data.limit_login) {
                               $("[name='limit_login1']").next().addClass('layui-form-checked');
                           }
                           if (data.limit_regist) {
                               $("[name='limit_regist1']").next().addClass('layui-form-checked');
                           }

                           $("[name='content']").val(data.content);
                           if (data.type) {
                               $("#b").next().addClass('layui-form-radioed');
                               $("#b").next().children("i").html("&#xe643;");
                               $("#b").next().children("i").addClass('layui-anim-scaleSpring');
                           } else {
                               $("#a").next().addClass('layui-form-radioed');
                               $("#a").next().children("i").html("&#xe643;");
                               $("#a").next().children("i").addClass('layui-anim-scaleSpring');
                           }

                           if (data.limit_time == "永久禁止"){
                               $("input[name ='limit_time']").val("");
                           }else {
                               $("input[name ='limit_time']").val(data.limit_time);
                           }
                           $("#id").val(data.id);
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
                    table.reload('demo', {
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
                        title: ['新增限制地址', 'font-size:18px;'],
                        skin: 'layui-layer-rim',
                        area: ['500px', 'auto'],
                        offset: '100px',
                        content: "<form class=\"layui-form\" action=\"{{url('admin/restrict/store')}}\"  method=\"post\" id=\"form\">\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">限制地址:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"text\" name=\"ip\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">选项:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"checkbox\" name=\"limit_login\" title=\"限制登录\"  value=\"1\">\n" +
                        "                <input type=\"checkbox\" name=\"limit_regist\" title=\"限制注册\" value=\"1\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">失效时间:</label>\n" +
                        "            <div class=\"layui-input-block\" >\n" +
                        "                <input type=\"text\" name=\"limit_time\" id=\"test1\" class=\"layui-input\" style=\"width: 300px\">\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">警告:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <div class=\"layui-form-mid layui-word-aux\" style=\"font-weight: bold;color: black;\">失效时间不填写,则默认为永久限制!</div>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">备注:</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <textarea placeholder=\"请输入内容\" name=\"content\" class=\"layui-textarea\" style=\"width: 300px\"></textarea>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "        <div class=\"layui-form-item\">\n" +
                        "            <label class=\"layui-form-label\">类型</label>\n" +
                        "            <div class=\"layui-input-block\">\n" +
                        "                <input type=\"radio\" name=\"type\" value=\"0\" title=\"IP\" checked=\"\">\n" +
                        "                <input type=\"radio\" name=\"type\" value=\"1\" title=\"机器码\">\n" +
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
                            laydate.render({
                                elem: '#test1',
                                type: 'datetime'
                            });
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
