<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','权限设置')
@section('content')
        <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
                <div class="layui-field-box">
                    <div class="layui-col-xs12">
                        <a href="{{url('admin/role/create')}}">
                            <button class="layui-btn layui-btn-normal">添加角色</button>
                        </a>
                    </div>
                    <table class="layui-table">
                        <colgroup>
                            <col width="100">
                            <col width="100">
                            <col width="500">
                            <col width="100">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>角色名称</th>
                            <th>拥有权限</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $lev)
                            <tr>
                                <td>{{$lev->role_id}}</td>
                                <td>{{$lev->name}}</td>
                                <td>
                                    {{--@foreach ($roles->nodes() as $lev)
                                        {{$lev['node_id']}}
                                    @endforeach--}}
                                </td>
                                <td>
                                    <a href="{{url("admin/role/{$lev->id}/edit")}}">
                                        <button class="layui-btn layui-btn-small">
                                            <i class="layui-icon">&#xe642;</i>
                                        </button>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{url("admin/role/{$lev->id}/del")}}" title="删除角色" onclick="return confirm('确定要删除此角色吗?')">
                                        <button class="layui-btn layui-btn-small layui-btn-danger">
                                            <i class="layui-icon">&#xe640;</i>
                                        </button>
                                    </a>
                                </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </fieldset>

@endsection
