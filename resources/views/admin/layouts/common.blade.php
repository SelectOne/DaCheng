<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- 公共的css -->
    @section('common_css')
        <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}"/>
    @show
    @section('js')
        <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
        <script type="text/javascript" src="{{asset('layui/layui.all.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/jquery-2.1.0.js')}}"></script>
    @show

</head>
    <!-- 内容主体区域 -->
<body class="childrenBody">
  @yield('content')
</body>
<script>-
    layui.use('layer', function(){
        @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
        layer.msg("{{$error}}");
        @endforeach
        @endif
        @if (session('success')==1)
        layer.msg("{{session('msg')}}");
        @endif
    });
</script>
</html>