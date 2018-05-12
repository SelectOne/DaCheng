<!-- 继承通用的模板 -->
@extends('admin.layouts.common')

<!-- 标题 -->
@section('title','权限设置')
@section('content')
    <fieldset class="layui-elem-field site-demo-button" style="margin-top: 10px;">
              <div class="layui-field-box layui-clear"  >
                  <div class="layui-col-md6" style="margin-left: 10%;margin-top: 50px;">
                       <form class="layui-form" action="{{url('admin/role')}}"  method="post">
                           {{ csrf_field()}}
                           <div class="layui-form-item">
                               <label class="layui-form-label">角色名称</label>
                               <div class="layui-input-block">
                                   <input type="text" name="name" class="layui-input" >
                               </div>
                           </div>
                           <div class="layui-form-item">
                               <label class="layui-form-label">拥有权限 :</label>
                           </div>
                           @foreach($nodes as $v)
                               @if($v['pid']==0)
                                   <div class="layui-form-item">
                                       <div class="layui-input-block">
                                           <input type="checkbox" name="node_id[]" value="{{$v['node_id']}}" title="{{$v['name']}}">
                                           <br>
                                           @foreach($nodes as $vv)
                                               @if($vv['pid']==$v['node_id'])
                                                   <input type="checkbox" name="node_id[]" value="{{$vv['node_id']}}" title="{{$vv['name']}}">
                                               @endif
                                           @endforeach
                                       </div>
                                   </div>
                               @endif
                           @endforeach
                          <div class="layui-form-item">
                              <div class="layui-input-block">
                                  <button class="layui-btn" lay-submit="" lay-filter="formDemo">立即提交</button>
                                  <a href="{{url('admin/role')}}"><button  type="button" class="layui-btn layui-btn-primary">返回</button></a>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
      </fieldset>

@endsection
