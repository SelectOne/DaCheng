<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CardRequest;
use App\Repositories\CardInfoRepository;
use App\Repositories\CardRepository;
use App\Repositories\TypeRepository;
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
            return json_encode(['code'=>1,'msg'=>'删除成功']);
        } else {
            return json_encode(['code'=>0,'msg'=>'删除失败']);
        }
    }


    public function getData(CardRequest $request)
    {
        $arr = $request->filter();
//        dd($arr);
//        dd(parent::TableApi($arr, $this->repository));
        return parent::TableApi($arr, $this->repository);
    }
}
