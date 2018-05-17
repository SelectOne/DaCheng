<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CardRequest;
use App\Http\Requests\TypeRequest;
use App\Repositories\CardInfoRepository;
use App\Repositories\CardRepository;
use App\Repositories\TypeRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardController extends BaseController
{
    private $repository;

    /**
     * CardController constructor.
     */
    public function __Construct(CardRepository $cardRepository)
    {
        $this->repository = $cardRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TypeRepository $typeRepository)
    {
        $items = $typeRepository->all();
        Helper::plog("查看实卡管理", 1);
        return view("admin.card.index", compact("items"));
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
     * @param CardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardRequest $request, CardInfoRepository $infoRepository)
    {
        $data = $request->options();
        $this->repository->creatCard($data);
        Helper::plog("新增实卡", 2);
        return redirect()->route("card.index")->with(['code'=>1, 'msg'=>"添加成功"]);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        if ($this->repository->delete($id)) {
            Helper::plog("删除实卡ID=".$id, 2);
            return json_encode(['code'=>1,'msg'=>'删除成功']);
        } else {
            return json_encode(['code'=>0,'msg'=>'删除失败']);
        }
    }

    // 会员卡数据
    public function getData(CardRequest $request)
    {
        $arr = $request->filter();
        return parent::TableApi($arr, $this->repository);
    }

    // 库存数据
    public function getData1(Request $request, CardInfoRepository $repository)
    {
        $arr = $request->all();
        return parent::TableApi($arr, $repository);
    }

    //类型管理
    public function getData2(Request $request, TypeRepository $repository)
    {
        $arr = $request->all();
        return parent::TableApi($arr, $repository);
    }
}
