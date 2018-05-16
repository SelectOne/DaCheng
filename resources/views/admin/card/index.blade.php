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
                                        <input type="text" name='created_time' class="layui-input" id="created_time" placeholder="生成日期" value="{{ old('created_time') }}">
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
            <div class="layui-tab-item">
                <blockquote>
                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                        <legend>基本信息</legend>
                    </fieldset>
                    <form class="layui-form" action="card" method="POST">
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label">会员卡类型</label>
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <select name="type_id" lay-verify="required" lay-filter="type_id">
                                        <option value="">请选择</option>
                                        @foreach ($items as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生成数量</label>
                            <div class="layui-input-block">
                                <input type="number" name="card_num" lay-verify="required|number" value="" class="layui-input" style="width: 190px">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">价格</label>
                            <div class="layui-input-block">
                                <input type="number" name="card_price1" value="" class="layui-input" style="width: 190px" disabled>
                            </div>
                        </div>
                        <input type="hidden" name="card_price" value="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">赠送金币</label>
                            <div class="layui-input-block">
                                <input type="number" name="given" lay-verify="required|number" value="" class="layui-input" style="width: 190px">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">有效日期</label>
                            <div class="layui-input-block">
                                <input type="text" name="expire_time" lay-verify="required" class="layui-input" id="expire_time" placeholder="截止日期" style="width: 190px">
                            </div>
                        </div>
                        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                            <legend>使用限制</legend>
                        </fieldset>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="">单个用户最大使用量</label>
                            <div class="layui-input-inline">
                                <input type="number" name="max_use" value="" class="layui-input" style="width: 190px">
                            </div>
                            <div class="layui-form-mid " style="font-weight: bold;color: red;">若不填写,则默认为无限制!</div>
                        </div>
                        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                            <legend>卡号规则</legend>
                        </fieldset>
                        <div class="layui-form-item">
                            <label class="layui-form-label">开始字符</label>
                            <div class="layui-input-inline">
                                <input type="text" name="card_first" placeholder="如: A" lay-verify="required|first" value="" class="layui-input" style="width: 190px">
                            </div>
                            <div class="layui-form-mid " style="font-weight: bold;color: black;">开始字符可为空,最大为一位</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">卡号长度</label>
                            <div class="layui-input-inline">
                                <input type="number" name="card_length" lay-verify="required" value="" class="layui-input" style="width: 190px">
                            </div>
                            <div class="layui-form-mid " style="font-weight: bold;color: black;">卡号长度必须大于或等于10小于等于16位</div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </blockquote>
            </div>
            <div class="layui-tab-item">3</div>
            <div class="layui-tab-item">4</div>
        </div>
    </div>
    <style>
        .layui-form-label {
            width: 142px;
        }
    </style>
    <script>
        layui.use(['table', 'form', 'laydate', 'element'], function() {
            var table = layui.table,
                form = layui.form,
                laydate = layui.laydate,
                element = layui.element;

            form.render();

            //自定义验证规则
            form.verify({
                first: function(value){
                    if(value.length > 1){
                        return '开始字符不能大于两位';
                    }
                    if( ! /^[a-zA-Z]+$/.test(value)){
                        return '开始字符必须是字母';
                    }
                }, length: function(value){
                    if( ! /^\w{9,15}$/.test(value)){
                        return '卡号长度大于或等于10小于等于16位';
                    }
                }
            });

            laydate.render({
                elem: '#expire_time'
                ,type: 'datetime'
            });

            element.render();

            // 监听Select选择框
            form.on('select(type_id)', function(data){
                if (data.value){
                    $.getJSON("getPrice/"+data.value, function (msg) {
                        console.log(msg)
                        $("input[name='card_price']").val(msg)
                        $("input[name='card_price1']").val(msg)
                    })
                } else {
                    $("input[name='card_price']").val("")
                    $("input[name='card_price1']").val("")
                }
                // console.log(data)


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
                    , {field: 'card_id', title: '卡号'}
                    , {field: 'created_time', title: '生成日期', width:200}
                    , {field: 'admin_name', title: '管理员'}
                    , {field: 'card_name', title: '实卡名称'}
                    , {field: 'card_num', title: '实卡数量', sort: true}
                    , {field: 'card_price', title: '实卡价格', sort: true}
                    , {field: 'given', title: '赠送金币', sort: true}
                    , {field: 'ip', title: 'IP'}
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
                        var token = "{{csrf_token()}}";
                        $.ajax({
                            url: 'card/' + data.id,
                            data: {'_token':token},
                            type:"DELETE",
                            contentType:"application/x-www-form-urlencoded",
                            dataType:"json",
                            success:function(data){
                                if (data.code == 1) {
                                    layer.alert(data.msg, {icon: 1});
                                    obj.del();
                                } else {
                                    layer.alert(data.msg, {icon: 2});
                                };
                            }
                        });
                        obj.del();
                        layer.close(index);
                    });
                }
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
                            id: $("input[name='card_id']").val()
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

