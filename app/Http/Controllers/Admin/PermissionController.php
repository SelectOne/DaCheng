<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionReRequest;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    private $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.role.permission");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return redirect("admin/permission/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        } else {
            return redirect("admin/permission/index")->withErrors("操作失败!");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $arr = $request->only('id', 'display_name', 'description');
        $arr['updated_time'] = time();
        if ($this->repository->update($arr, $arr['id'])) {
            return redirect("admin/permission/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        } else {
            return redirect("admin/permission/index")->withErrors("操作失败!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function destroy(Request $request)
    {
        $id = $request->get("id");

        if ($this->repository->delete($id)) {
            echo "删除成功!";
        } else {
            echo "删除失败!";
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
