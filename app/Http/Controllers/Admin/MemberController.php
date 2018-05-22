<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Repositories\RoomRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository as MRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\Helper;

class MemberController extends BaseController
{
    private $MRepository;

    public function __construct(MRepository $MRepository)
    {
        $this->MRepository = $MRepository;
//        $this->middleware("member");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        dd(Auth::check());
        $input = $request->all();
        Helper::plog("查看用户列表", 1);
        return view("admin.member.index", compact("input"));
    }

    public function getData(Request $request)
    {
        $arr = $request->all();
//        dd($arr);
        return parent::TableApi($arr, $this->MRepository);
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

    /**
     * 功能: 冻结与解冻
     * @param Request $request
     * @return string
     */
    public function checkStatus(Request $request)
    {
        $data = $request->all();
        $rs = $this->MRepository->update( ['status'=>$data['status']], $data['id'] );
        if ($rs) {
            Helper::plog("冻结与解冻用户ID:".$data['id'], 2);
            return json_encode(['status'=>'ok', 'msg'=>"操作成功!"]);
        } else {
            return json_encode(['status'=>'notok', 'msg'=>"操作失败!"]);
        }
    }

    /**
     * 充值
     * @param MemberRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function recharge(MemberRequest $request)
    {
        $data = $request->all();
        $this->MRepository->recharge($data['id'],$data['num']);

//        Helper::plog("后台充值,用户ID:".$data['id'], 2);
        return redirect("admin/member/index")->with(["success"=>1, "msg"=>"充值成功!"]);
    }

    /**
     * 每日在线玩家统计图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statistics()
    {
        $num = $this->MRepository->total();
        return view("admin.data.register", compact("num"));
    }

    /**
     * 每日在线玩家统计数据
     * @param MemberRequest $request
     * @return string
     */
    public function cztj(MemberRequest $request)
    {
//        dd('sda');
        $time = $request->time();
        $data = $this->MRepository->register($time);
        return $data;
    }

    /**
     * 在房间玩家
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inRoom(RoomRepository $roomRepository)
    {
        $rooms = $roomRepository->all();
        return view("admin.data.inRoom", compact("rooms"));
    }

    /**
     * 在房间玩家数据
     * @param Request $request
     * @return array
     */
    public function mInRoom(Request $request)
    {
        $arr = $request->all();
        $data = $this->MRepository->mInRoom($arr);
        $count = $data['count'];
        unset($data['count']);
        return ['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$data];
    }

    /**
     * 活跃玩家展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activePlayer()
    {
        $data = $this->lively2();
        return view("admin.data.active", compact('data'));
    }

    /**
     * 每天活跃时长大于1小时玩家数
     * @param MemberRequest $request
     * @return string
     */
    public function lively1(MemberRequest $request)
    {
        $time = $request->time();
        $data = $this->MRepository->lively1($time);
//        var_dump($data);exit;
        return $data;
    }

    /**
     * 每月活跃时长玩家数
     * @return array|mixed
     */
    public function lively2()
    {
        $data = $this->MRepository->lively2();
        $total = $this->MRepository->total(0);
        $data = array_map(function ($v) use($total){
            return round($v/$total, 2, PHP_ROUND_HALF_EVEN);
        }, $data);
        return $data;
    }
}
