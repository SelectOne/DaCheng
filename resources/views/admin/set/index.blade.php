<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <form class="layui-form" action="settings/{{$id}}" method="POST">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <fieldset class="layui-elem-field layui-field-title">
            <legend>签到赠送金币</legend>
            @foreach($arr as $v)
                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 190px">第{{$v->days}}天</label>
                    <div class="layui-input-block">
                        <input type="number" name="reward[{{$v->days}}]" lay-verify="required|number" value="{{$v->reward}}" class="layui-input" style="width: 190px">
                    </div>
                </div>
            @endforeach
        </fieldset>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 190px">注册赠送金币</label>
            <div class="layui-input-block">
                <input type="number" name="param2" lay-verify="required|number" value="{{$rows['param2']}}" class="layui-input" style="width: 190px">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 190px">同一机器码限制注册数量</label>
            <div class="layui-input-inline">
                <input type="number" name="param3" value="{{$rows['param3']}}" class="layui-input" style="width: 190px">
            </div>
            <div class="layui-form-mid " style="font-weight: bold;color: black;">0表示无限制</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 190px">同一机IP限制注册数量</label>
            <div class="layui-input-inline">
                <input type="number" name="param4" value="{{$rows['param4']}}" class="layui-input" style="width: 190px">
            </div>
            <div class="layui-form-mid " style="font-weight: bold;color: black;">0表示无限制</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

    <script>
        layui.use(['form'], function () {
          var form = layui.form;
          form.render();
        })
    </script>
@endsection