<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','首页')
<!-- 内容 -->
@section('content')
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>汇总</legend>
    </fieldset>
    <table class="layui-table">
        <thead>
        <tr>
            <th>充值总金额(元)</th>
            <th>注册赠送金币</th>
            <th>后台赠送金币</th>
            <th>任务赠送金币</th>
            <th>签到赠送金币</th>
            <th>系统金币输(-)赢(+)</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$recharge}}</td>
                <td>{{$data[0]}}</td>
                <td>{{$data[1]}}</td>
                <td>{{$data[2]}}</td>
                <td>{{$data[3]}}</td>
                @if ( $sum > 0 )
                    <td><span style="color: darkgreen">{{$sum}}</span></td>
                @else
                    <td><span style="color: red">{{$sum}}</span></td>
                @endif
            </tr>
        </tbody>
    </table>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>游戏汇总</legend>
    </fieldset>
    <table class="layui-table">
        <thead>
        <tr>
            <th>游戏序号</th>
            <th>游戏名称</th>
            <th>系统金币输(-)赢(+)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>
                @if ( $row->num > 0 )
                    <td><span style="color: darkgreen">{{$row->num}}</span></td>
                @else
                    <td><span style="color: red">{{$row->num}}</span></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection