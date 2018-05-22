<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CoinChangeRequest;
use App\Repositories\CoinChangeRepository;
use App\Repositories\GivenRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoomRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 首页
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index.index');
    }



    /**
     * 公用后台
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function main()
    {
        return view('admin.index.main');
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

    public function collect(GivenRepository $givenRepository, OrderRepository $orderRepository, RoomRepository $roomRepository)
    {
        $recharge = $orderRepository->total();
        $data = $givenRepository->getAll();
        $sum = $roomRepository->sum();
        $rows = $roomRepository->getAll();
        return view("admin.data.list", compact("data", "recharge", "sum", "rows"));
    }

    public function coinChange()
    {
        return view('admin.data.coinChange');
    }

    public function coin(CoinChangeRequest $request, CoinChangeRepository $coinChangeRepository)
    {
        $arr = $request->filter();
        $data = $coinChangeRepository->limit($arr);
        $count = $data['count'];
        unset($data['count']);
        return ['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data];
    }
}
