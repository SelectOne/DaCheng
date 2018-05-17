<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Repositories\RoleRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    private $repository;

    public function  __construct(AdminRepository $adminRepository){
        $this->repository = $adminRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::plog("查看实卡管理", 1);
        return view("admin.role.admin");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Admin $admin, RoleRepository $roleRepository)
    {
        $roles = $roleRepository->getRoles();
        return view("admin.role.create_update", compact("admin", "roles"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only("admin_name");
        $role = $request->get("role");
        $this->repository->create1($data, $role);
        Helper::plog("新增管理员", 2);
        return redirect("admin/admin")->with(["success"=>1, "msg"=>"操作成功!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, RoleRepository $roleRepository)
    {
        $admin = $this->repository->first($id);
        $roles = $roleRepository->getRoles();
        return view('admin.role.create_update',compact('roles','admin'));
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
        $role = $request->get('role');
        $this->repository->update1($id, $role);
        Helper::plog("修改管理员,ID=".$id."的信息", 2);
        return redirect("admin/admin")->with(["success"=>1, "msg"=>"操作成功!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 表格数据接口
     * @param Request $request
     * @return json
     */
    public function getData(Request $request, RoleRepository $roleRepository)
    {
        $arr = $request->all();
        $data = $this->repository->limit($arr, $roleRepository);
        $count = $this->repository->getCount($arr);
        return json_encode(['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data]);
    }
}
