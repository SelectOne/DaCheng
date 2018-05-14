<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','管理员设置')
@section('content')
    <blockquote class="layui-elem-quote layui-text">
        {{isset($admin->admin_id)? '编辑管理员':'添加管理员'}}
    </blockquote>
    @if(isset($admin->admin_id))
        <form class="layui-form" action="{{ route('admin.update', $admin->admin_id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @else
                <form class="layui-form" action="{{ route('admin.store') }}" method="POST">
                    @endif
                    {{ csrf_field() }}
                    <div class="layui-form-item">
                        <label class="layui-form-label">管理员名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="admin_name"  autocomplete="off" placeholder="请输入角色名称" class="layui-input" value="{{ old('admin_name', $admin->admin_name ) }}" @if(isset($admin->admin_id)) disabled @endif>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色名称</label>
                        <div class="layui-input-block">
                            @foreach($roles as $k => $role)
                                <input type="checkbox" name="role[]" value="{{ $k }}" title="{{ $role }}" {{ (isset($admin->role) && in_array($k,$admin->role))?'checked':'' }} lay-skin="primary">
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
