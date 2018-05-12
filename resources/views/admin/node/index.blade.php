<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','权限设置')
@section('content')
    <div class="layui-field-box">
        <div class="layui-col-xs12">
            <button class="layui-btn layui-btn-normal create">添加节点</button>
        </div>
        <table class="layui-table">
            <colgroup>
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>排序</th>
                <th>名称</th>
                <th>路由</th>
                <th>图标</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($znode as $v)
                <tr>
                    <td>{{$v->node_id}}</td>
                    <td>{{$v->sort}}</td>
                    <td>{{$v->name}}</td>
                    <td>{{$v->route}}</td>
                    <td>{{$v->icon}}</td>
                    <td>
                        <a class="show" data-url="{{url("admin/node/{$v->node_id}")}}">编辑</a>
                        &nbsp;|&nbsp;
                        <form action="{{url("admin/node/{$v->node_id}")}}" method="post" class="form">
                            <input name="_method" type="hidden" value="delete">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <a class="del" data-url="">
                                删除
                            </a>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="page" >{{$znode->render()}}</td>
            </tr>
            </tbody>
        </table>


    </div>

    <style>
        .pagination {
            float: left;
            display: inline-block;
        }
        .bg{width:100%;height: 100%;background: rgba(0,0,0,0.3);position: fixed;top:0;left:0;z-index: 1000;}
        .bg .sfz-detial{width: 600px;height: 500px;background: #ffffff;z-index: 100000;margin: 200px auto;position: relative;}
        .bg .sfz-detial .img-sfz{width:100%;height: 270px;margin-top: 60px;}

        /*.bg .sfz-detial .img-sfz img{width: 100%;height: 100%;margin-top: 30px;}*/
        /*.bg button{width: 100px;height:50px; border-radius: 20px;float: left;}*/
        .sfz-detial>span{position: absolute;right: 0;top:-5px;color: #666666;font-size: 18px;cursor: pointer;}
        a{color: #404040;}
    </style>
    <div class="bg" style="display: none">
        <div class="sfz-detial">
            <span>X</span>
            <div class="img-frant img-sfz">
                <div class="layui-field-box layui-clear"  >
                    <div class="layui-col-md6" style="margin-left: 10%;margin-top: 50px;">
                        <form class="layui-form" action="{{url('admin/node')}}"  method="post">
                            {{ csrf_field()}}
                            <div class="layui-form-item">
                                <label class="layui-form-label">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" name="sort" class="layui-input" id="sort">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-block">
                                    <select lay-verify="required" name="type" id="type">
                                        <option value="0">主节点</option>
                                        <option value="1">子节点</option>
                                        <option value="2">操作节点</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属节点</label>
                                <div class="layui-input-block" >
                                    <select name="pid" id="pid">
                                        <option value="0">根节点</option>
                                        @foreach($znode as $v)
                                            <option value="{{$v['node_id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" class="layui-input" id="name">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">路由</label>
                                <div class="layui-input-block">
                                    <input type="text" name="route" class="layui-input" id="route">
                                    <span class="help-inline">格式：admin/node/create</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">是否菜单</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="is_menu" id="is_menu" lay-skin="switch" title="是否" lay-text="是|否">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="formDemo">确定</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset("layui/layui.js")}}" charset="utf-8"></script>
    <script src="{{asset("layui/layui.all.js")}}" charset="utf-8"></script>
    {{--<script src="//res.layui.com/layui/dist/layui.js" charset="utf-8"></script>--}}
    <!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
    <script type="text/javascript">
        layui.use('layer', function() {
            var $ = layui.$
            //Demo
            $(".create").click(function () {
                $('#sort').val("");
                $('#type').val("");
                $('#pid').val("");
                $('#name').val("");
                $('#route').val("");
                $('.bg').show();
            });
            $('.bg span').click(function () {
                $('.bg').hide();
            });

            $(".show").click(function () {
                var url = $(this).attr('data-url');
                $.getJSON(url,function (data) {
                    alert(data.is_menu);
                    $('#sort').val(data.sort);
                    $('#type').val(data.type);
                    $('#pid').val(data.pid);
                    $('#name').val(data.name);
                    $('#route').val(data.route);
                    if (data.is_menu > 0) {
                        $('.layui-form-switch ').addClass("layui-form-onswitch");
                    }
                    $('#sort').val(data.sort);
                    $('.bg').show();
                })
            })

            $(".del").click(function () {
                var url = $(this).attr('data-url');
                $(this).parent().submit();
            })
        })
    </script>

@endsection
