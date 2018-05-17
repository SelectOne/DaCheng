<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TypeRequest;
use App\Repositories\TypeRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends BaseController
{
    private $repository;

    public function __Construct(TypeRepository $typeRepository)
    {
        $this->repository = $typeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(TypeRequest $request)
    {
        $arr = $request->options();
//        dd($arr);
        if ($this->repository->create($arr)) {
            Helper::plog("新增类型", 2);
            return redirect("admin/card#tab=4")->with(["success"=>1, "msg"=>"新增成功!"]);
        } else {
            return redirect()->route("card.index")->withErrors("新增失败!");
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
    public function update(TypeRequest $request, $id)
    {
        $arr = $request->options();

        if ($this->repository->update($arr, $id)) {
            Helper::plog("修改类型--ID为".$id."的信息", 2);
            return redirect("admin/card#tab=4")->with(["success"=>1, "msg"=>"修改成功!"]);
        } else {
            return redirect()->route("card.index")->withErrors("修改失败!");
        }
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
}
