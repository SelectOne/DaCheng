<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RestrictRequest;
use App\Repositories\RestrictRepository;
use App\Services\Helper;
use Illuminate\Database\QueryException;
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
        Helper::plog("查看限制列表", 1);
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

        try{
            $this->repository->create($arr);
            return redirect("admin/restrict/index")->with(["success"=>1, "msg"=>"操作成功!"]);
        }
        catch (\Illuminate\Database\QueryException $e) {
            $error_code = $e->errorInfo[0];
            if ($error_code == 23000) {
                return redirect("admin/restrict/index")->withErrors("IP或机器码请不要重复!");
            }
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
            Helper::plog("修改限制地址ID=".$arr['id'], 2);
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
            Helper::plog("删除限制地址ID=".$id, 2);
            echo "删除成功!";
        } else {
            echo "删除失败!";
        }
    }

    public function getData(RestrictRequest $request)
    {
        $arr = $request->filter();
        return parent::TableApi($arr, $this->repository);
    }
}
