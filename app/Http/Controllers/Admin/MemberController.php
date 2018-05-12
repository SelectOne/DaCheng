<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository as MRepository;


class MemberController extends BaseController
{
    private $MRepository;

    public function __construct(MRepository $MRepository)
    {
        $this->MRepository = $MRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        return view("admin.member.index", compact("input"));
    }

    public function getData(Request $request)
    {
        $arr = $request->all();
//        dd($arr);
        $members = $this->MRepository->limit($arr);
        $count   = $this->MRepository->getCount($arr);
        return json_encode(['code'=>0,'msg'=>'成功','count'=>$count, 'data'=>$members]);
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
        $rs = $this->MRepository->recharge($data['id'],$data['num']);
        if ($rs) {
            return redirect("admin/member/index")->with(["success"=>1, "msg"=>"充值成功!"]);
        } else {
            return redirect("admin/member/index")->withErrors("充值失败!");
        }
    }

}
