<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class OrderController extends BaseController
{
    private $repository;

    public function __Construct(OrderRepository $orderRepository)
    {
        $this->repository = $orderRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::plog("查看订单列表",1);
        return view("admin.order.index");
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
        //
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getData(OrderRequest $request)
    {
        $arr = $request->filter();
        return Parent::TableApi( $arr, $this->repository );
    }

    /**
     * 充值统计/数据分析
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAmount()
    {
        $num1 = $this->repository->total();
        $num2 = $this->repository->rechargeNum();
        $num3 = $this->repository->rechargeNum(1);
        $num4 = $this->repository->rechargeTop();
        $num5 = $this->repository->type();
        $num6 = round($num1 / $num2, 2);
//        dd($num1, $num2, $num3, $num4, $num5, $num6);
        Helper::plog("查看充值统计", 1);
        return view("admin.data.amount",compact("num1", "num2", "num3", "num4", "num5", "num6"));
    }

    // 充值金额折线图数据
    public function orderAmount(OrderRequest $request)
    {
        $time = $request->time();
        $data = $this->repository->amount($time);
        return $data;
    }
}
