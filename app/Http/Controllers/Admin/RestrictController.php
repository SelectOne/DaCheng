<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RestrictRequest;
use App\Repositories\RestrictRepository;
use Illuminate\Http\Request;

class RestrictController extends BaseController
{
    private $repository;

    public function __construct(RestrictRepository $repository)
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
        return view("admin.restrict.index");
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
    public function store(RestrictRequest $request)
    {
        $arr = $request->options();

        if ($this->repository->create($arr)) {
            return redirect("admin/restrict/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        } else {
            return redirect("admin/restrict/index")->withErrors("操作失败!");
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
    public function update(RestrictRequest $request)
    {
        $arr = $request->options();

        if ($this->repository->update($arr, $arr['id'])) {
            return redirect("admin/restrict/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        } else {
            return redirect("admin/restrict/index")->withErrors("操作失败!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

    public function getData(RestrictRequest $request)
    {
        $arr = $request->filter();
        $data  = $this->repository->limit($arr);
        $count = $this->repository->getCount($arr);

        return json_encode(['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data]);
    }
}
