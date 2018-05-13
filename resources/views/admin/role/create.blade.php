<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','权限设置')
@section('content')
    <blockquote class="layui-elem-quote layui-text">
        {{isset($roles->id)? '编辑角色':'添加角色'}}
    </blockquote>
    @if(isset($roles->id))
        <form class="layui-form" action="{{ route('role.update', $roles->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @else
                <form class="layui-form" action="{{ route('role.store') }}" method="POST">
                    @endif
                    {{ csrf_field() }}
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required|name" autocomplete="off" placeholder="请输入角色名称" class="layui-input" value="{{ old('name', $roles->name ) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">显示名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="display_name" lay-verify="required|name" autocomplete="off" placeholder="请输入显示名称" class="layui-input" value="{{ old('name', $roles->display_name ) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">描述</label>
                        <div class="layui-input-inline">
                            <input type="text" name="description" lay-verify="required|name" autocomplete="off" placeholder="请输入描述" class="layui-input" value="{{ old('name', $roles->description ) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">权限名称</label>
                        <div class="layui-input-block">
                            @foreach($permissions as $k => $permission)
                                <input type="checkbox" name="permission[]" value="{{ $k }}" title="{{ $permission }}" {{ (isset($roles->permission) && array_key_exists($k,$roles->permission))?'checked':'' }} lay-skin="primary">
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="role">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>

                <script>
                    layui.use(['form'], function() {
                        var form = layui.form;
                        form.render();
                    })
                </script>

@endsection
