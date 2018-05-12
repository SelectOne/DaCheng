<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\NodeRepository;

class NodeController extends Controller
{
    private $NodeRepository;

    public function  __construct(NodeRepository $NodeRepository){
        $this->NodeRepository = $NodeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all  = $this->NodeRepository->getAll();
        $znode = $this->NodeRepository->getNode();
        return view("admin.node.index", compact("all","znode"));
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
        $input = $request->input();
        if ($request->get('status') == 'on') {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }
        unset($input['_token']);
        if ($this->NodeRepository->create($input)) {
            return redirect("admin/node")->with('success',"添加成功!");
        } else {
            return redirect("admin/node")->withErrors("添加失败!");
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
        $data = $this->NodeRepository->find($id);
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "sdasd";
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
        $rs = $this->NodeRepository->update(['is_menu'=>-1],$id,"node_id");
        if ($rs) {
            return redirect("admin/node")->with('success',"删除成功!");
        } else {
            return redirect("admin/node")->withErrors("删除失败!");
        }
    }
}
