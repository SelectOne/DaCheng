<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','实卡管理')
<!-- 内容 -->
@section('content')
    <div class="layui-tab layui-tab-card" lay-filter="demo">
        <ul class="layui-tab-title" id="tabHeader">
            <li class="layui-this" lay-id="1">会员卡管理</li>
            <li lay-id="2">会员卡生成</li>
            <li lay-id="3">库存统计</li>
            <li lay-id="4">类型管理</li>
        </ul>
        <div class="layui-tab-content" id="tabBody">
            <div class="layui-tab-item layui-show">
                <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
                    <div class="layui-field-box">
                        <div class="layui-col-xs12">
                            <blockquote class="layui-elem-quote layui-quote-nm layui-form">
                                <div class="layui-inline">
                                    <label class="layui-form-label">卡号</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="card_id" id="card_id"  class="layui-input" value="{{ old("card_id") }}" placeholder="ID">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">生成日期</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name='created_time' class="layui-input" id="created_time" placeholder="订单日期" value="{{ old('created_time') }}">
                                    </div>
                                </div>
                                <div class="demoTable layui-inline">
                                    <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
                                </div>
                            </blockquote>
                        </div>

                        <table id="demo" lay-filter="test"></table>
                        <script type="text/html" id="barDemo">
                            {{--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                        </script>
                    </div>
                </fieldset>
            </div>
            <div class="layui-tab-item">2</div>
            <div class="layui-tab-item">3</div>
            <div class="layui-tab-item">4</div>
        </div>
    </div>

    <script>
        layui.use(['table', 'form', 'laydate', 'element'], function() {
            var table = layui.table,
                form = layui.form,
                laydate = layui.laydate,
                element = layui.element;
            form.render();
            element.render();
            element.on('tab(demo)', function(data){
                console.log(this); //当前Tab标题所在的原始DOM元素
                console.log(data.index); //得到当前Tab的所在下标
                console.log(data.elem); //得到当前的Tab大容器
            });

            laydate.render({
                elem: '#created_time'
                ,range: '--'
            });

            table.render({
                elem: '#demo'
                , url: '{{ route("card.getData") }}' //数据接口
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
                }
            });
        });
    </script>
@endsection

