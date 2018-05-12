<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>后台管理系统</title>
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}" />
  <style>
      .layui-tab-item{
          height: 1000px;
      }
  </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">后台管理系统 v2.0</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
       {{-- <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">控制台</a></li>
            <li class="layui-nav-item"><a href="">商品管理</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
        </ul>--}}
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                    {{session('name')}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">修改密码</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="{{url('admin/logout')}}">退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item"><a href="">首页</a></li>
                {{--<li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;">权限管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="1" date-url="{{url('admin/admin')}}">管理员列表</a></dd>
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="2" date-url="{{url('admin/role')}}">角色列表</a></dd>
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="3" date-url="{{url('admin/node')}}">节点列表</a></dd>
                    </dl>
                </li>--}}
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">用户管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="1" date-url="{{url('admin/member/index')}}">用户列表</a></dd>
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="2" date-url="{{url('admin/restrict/index')}}">限制列表</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">数据分析</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="3" date-url="{{url('admin/member/index')}}">用户列表</a></dd>
                        <dd><a href="javascript:;" class="site-demo-active" data-type="tabAdd" idd="4" date-url="{{url('admin/restrict/index')}}">限制列表</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item"><a href="">发布商品</a></li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <div class="layui-tab" lay-filter="demo" lay-allowclose="true">
            <ul class="layui-tab-title">

            </ul>
            <div class="layui-tab-content" >

            </div>
        </div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © 后台管理系统 v2.0
    </div>
</div>
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('layui/layui.all.js')}}"></script>
<script>

    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;
        var $ = layui.jquery;
        var url = "{{url('admin/index')}}";
        element.tabAdd('demo', {
            title: '首页' //用于演示
            ,content: "<iframe src='"+url+"' style='height: 100%;width: 100%;border:0'></iframe>"
            ,id: 1000  //实际使用一般是规定好的id，这里以时间戳模拟下
        })
        //选中一个Tab项
        element.tabChange('demo',1000);

        //触发事件
        var active = {
            tabAdd: function(){
                var idd = $(this).attr('idd');
                //删除一个tab项
                element.tabDelete('demo', idd);
                //新增一个Tab项
                element.tabAdd('demo', {
                    title: $(this).text() //用于演示
                    ,content: "<iframe src='"+$(this).attr('date-url')+"' style='height: 100%;width: 100%;border:0'></iframe>"
                    ,id: idd  //实际使用一般是规定好的id，这里以时间戳模拟下
                })
                //选中一个Tab项
                element.tabChange('demo',idd);
            }
        };
        $('.site-demo-active').on('click', function(){
            var othis = $(this), type = othis.data('type');
            active[type] ? active[type].call(this, othis) : '';
        });
    });
</script>
</body>
</html>