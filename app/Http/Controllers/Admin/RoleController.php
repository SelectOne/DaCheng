<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleController extends Controller
{
    private $repository;

    public function  __construct(RoleRepository $RoleRepository){
        $this->repository = $RoleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Role $roles,PermissionRepository $permission)
    {
        $permissions = $permission->getAll();
        return view("admin.role.create", compact("roles","permissions"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only("name", "display_name", 'description');

        if ($this->repository->create($data)) {
            return redirect("admin/role/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        } else {
            return redirect("admin/role/index")->withErrors("操作失败!");
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, PermissionRepository $permission)
    {
        $roles = $this->repository->find($id);
//        dd($roles->permissions);
        $roles->permission = $permission->getPremissions($id);
        $permissions = $permission->getAll();
//        dd($roles, $permission);
        return view('admin.role.create',compact('roles','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only("name", "display_name", 'description');
        $permissions = $request->get("permission");
//        dd($permissions);
        $this->repository->update1($id, $data, $permissions);

        return redirect("admin/role")->with(["success"=>1, "msg"=>"操作成功!"]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->repository->delete($id)) {
            return json_encode(['code'=>1,'msg'=>'删除成功']);
        } else {
            return json_encode(['code'=>0,'msg'=>'删除失败']);
        }

    }

    /**
     * 表格数据接口
     * @param Request $request
     * @return json
     */
    public function getData(Request $request)
    {
        $arr = $request->all();
//        dd($arr);
        $data = $this->repository->limit($arr);
        $count = $this->repository->getCount($arr);
        return json_encode(['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data]);
    }
}