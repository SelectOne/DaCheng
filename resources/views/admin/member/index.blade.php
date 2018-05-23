<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','用户列表')
<!-- 内容 -->
@section('content')
        <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
                <div class="layui-field-box">
                    <div class="layui-col-xs12">
                        <blockquote class="layui-elem-quote layui-quote-nm">
                            <div class="layui-inline">
                                <label class="layui-form-label">ID</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="id" id="id"  class="layui-input" value="{{ $input['id']??"" }}" placeholder="ID">
                                </div>
                            </div>
                           <div class="layui-inline">
                               <label class="layui-form-label">昵称</label>
                               <div class="layui-input-inline">
                                   <input type="text" name="nickname"  class="layui-input" value="{{ old('nickname') }}" placeholder="昵称">
                               </div>
                           </div>
                           <div class="layui-inline">
                               <label class="layui-form-label">上级ID</label>
                               <div class="layui-input-inline">
                                   <input type="number" name="pid" value="{{ old('pid') }}"  class="layui-input" placeholder="上级ID">
                               </div>
                           </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">登录日期</label>
                                <div class="layui-input-inline">
                                    <input type="text" name='time' class="layui-input" id="time" placeholder="登录日期" value="{{ old('time') }}">
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
                    <button class="layui-btn" data-type="freeze">冻结</button>
                    <button class="layui-btn" data-type="unfreeze">解冻</button>
                    <button class="layui-btn" data-type="recharge">充值</button>
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
            {{#  if(d.pid != '0'){ }}
            <a href="?id={{ d.pid }}" class="layui-table-link">{{ d.pid }}</a>
            {{#  } else { }}
            {{ d.pid }}
            {{#  } }}
            @endverbatim
        </script>

        <script type="text/html" id="tpl2">
            @verbatim
            {{#  if(d.member_level == 1){ }}
            普通会员
            {{#  } else if (d.member_level == 2) { }}
            中级会员
            {{#  } else if (d.member_level == 3) { }}
            高级会员
            {{#  } }}
            @endverbatim
        </script>

        <script type="text/html" id="tpl3">
            @verbatim
            {{#  if(d.manage_level == 1){ }}
            普通会员
            {{#  } else if (d.manage_level == 2) { }}
            中级会员
            {{#  } else if (d.manage_level == 3) { }}
            高级会员
            {{#  } }}
            @endverbatim
        </script>

        <script type="text/html" id="tpl4">
            @verbatim
            {{#  if(d.status){ }}
            <span style="color: orangered;">已冻结</span>
            {{#  } else { }}
            正常
            {{#  } }}
            @endverbatim
        </script>

        <script type="text/html" id="tpl5">
            <a class="layui-table-link" href="{{url("admin/coinChange?mid=")}}@{{ d.id }}">@{{d.num}}</a>
        </script>

        <script>
            layui.use('laydate', function(){
                var laydate = layui.laydate;
                //执行一个laydate实例
                //日期范围
                laydate.render({
                    elem: '#time'
                    ,range: '--'
                });
            });

            layui.use('table', function() {
                var table = layui.table;

                table.render({
                    elem: '#demo'
                    , url: '{{url("admin/member/getData")}}' //数据接口
                    , where: {
                        id: $('#id').val()
                        ,ip: "{{ $input['ip']??"" }}"
                        ,machine_ip: "{{ $input['machine_ip']??"" }}"
                    }
                    , method: 'get'
                    , width: 1640
                    , height: 501
                    , page: true //开启分页
                    , cols: [[ //表头
                         {type: 'checkbox', fixed: 'left'}
                        , {field: 'id', title: '用户ID', width: 100, unresize: true, sort: true, fixed: "left"}
                        , {field: 'game_id', title: '游戏ID', width: 100, unresize: true, sort: true}
                        , {field: 'pid', title: '上级代理', width: 100, unresize: true, sort: true, templet: '#tpl1'}
                        , {field: 'openid', title: '用户名', width: 120,}
                        , {field: 'nickname', title: '昵称', width: 100,}
                        , {field: 'realname', title: '真实姓名', width: 100,}
                        , {field: 'sex', title: '性别', width: 80, unresize: true, sort: true, templet: '#sexTpl'}
                        , {field: 'num', title: '金币', width: 100, unresize: true, sort: true, templet: '#tpl5'}
                        , {field: 'strongbox', title: '保险柜', width: 100, unresize: true, sort: true}
                        , {field: 'ip', title: 'IP地址', width: 110, unresize: true}
                        , {field: 'member_level', title: '会员级别', width: 100, unresize: true, sort: true, templet: '#tpl2'}
                        , {field: 'manage_level', title: '管理级别', width: 100, unresize: true, sort: true, templet: '#tpl3'}
                        , {field: 'loginnum', title: '登陆次数', width: 100, unresize: true, sort: true}
                        , {field: 'status', title: '状态', width: 100, sort: true, templet: '#tpl4'}
                        , {field: 'login_time', title: '登录日期', sort: true}
                        , /*{fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}*/
                    ]]
                });

                //排序
                table.on('sort(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    // console.log(obj.field); //当前排序的字段名
                    // console.log(obj.type); //当前排序类型：desc（降序）、asc（升序）、null（空对象，默认排序）
                    // console.log(this); //当前排序的 th 对象

                    //尽管我们的 table 自带排序功能，但并没有请求服务端。
                    //有些时候，你可能需要根据当前排序的字段，重新向服务端发送请求，如：
                    table.reload('demo', {
                        initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。 layui 2.1.1 新增参数
                        ,where: { //请求参数
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
                                ,nickname: $("input[name='nickname']").val()
                                ,pid: $("input[name='pid']").val()
                                ,time: $("input[name='time']").val()
                            }
                        });
                    },Excel:function() {
                        table.reload('demo', {
                            // 点击查询和刷新数据表会把以下参数传到后端进行查找和分页显示
                            where: {
                                Excel: $("input[name='Excel']").val()
                            }
                        });
                    },freeze: function(){ //冻结
                        var checkStatus = table.checkStatus('demo')
                            ,data = checkStatus.data;
                        //判断是否全部数据
                        if (checkStatus.isAll || data.length > 2) {
                            data.forEach(function (item) {
                                console.log(item.id)
                            })
                            layer.msg("为了数据的安全性,请不要全选,多选!")
                        } else if(data.length == 1) {
                            if (data[0].status == 1) {
                                layer.msg("该用户已冻结!")
                            } else {
                                layer.confirm('是否确定冻结该用户？', {
                                    btn: ['确定', '取消'] //可以无限个按钮
                                    ,
                                }, function(index, layero){
                                    var id = data[0].id;

                                    $.ajax({
                                        methord:"get",
                                        url:"{{url("admin/member/checkStatus")}}",
                                        dataType:"json",
                                        data:{
                                            'id':id,
                                            'status':1
                                        },
                                        success:function (res) {
                                            layer.msg(res.msg)
                                             setTimeout(function () {
                                                 location.reload()
                                             },600)
                                        }
                                    })
                                    layer.close(index)

                                }, function(index, layero){
                                    layer.close(index)
                                })
                            }
                        }
                    },unfreeze: function () {
                        var checkStatus = table.checkStatus('demo')
                            ,data = checkStatus.data;
                        if (data.length == 1) {
                            if (data[0].status != 1) {
                                layer.msg("该用户尚未冻结!")
                            } else {
                                layer.confirm('是否确定解冻该用户？', {
                                    btn: ['确定', '取消'] //可以无限个按钮
                                    ,
                                }, function(index, layero){
                                    var id = data[0].id;

                                    $.ajax({
                                        methord:"get",
                                        url:"{{url("admin/member/checkStatus")}}",
                                        dataType:"json",
                                        data:{
                                            'id': id,
                                            'status': 0
                                        },
                                        success:function (res) {
                                            layer.msg(res.msg)
                                            setTimeout(function () {
                                                location.reload()
                                            },600)
                                        }
                                    })
                                    layer.close(index)

                                }, function(index, layero){
                                    layer.close(index)
                                })
                            }
                        }
                    },recharge: function () {
                        var checkStatus = table.checkStatus('demo')
                            ,data = checkStatus.data;
                        if (data.length == 1) {
                            layer.open({
                                id: 1,
                                type: 1,
                                title: '充值',
                                skin: 'layui-layer-rim',
                                area: ['250px', 'auto'],
                                content: '<form action="{{url('admin/member/recharge')}}" id="chongzhi"><input name="id" type="hidden" id="hd" value=""><input id="num" name="num" type="text" class="layui-input" placeholder="请输入数字"></form>',
                                btn: ['确定', '取消'],
                                btn1: function (index, layero) {
                                    $("#hd").val(data[0].id);
                                    $('#chongzhi').submit();
                                    layer.close(index);
                                },
                                btn2: function (index, layero) {
                                    layer.close(index);
                                }
                            });
                        }
                    }
                };

                $('.demoTable .layui-btn').on('click', function(){
                    var type = $(this).data('type');
                    active[type] ? active[type].call(this) : '';
                });
            });
        </script>
@endsection
