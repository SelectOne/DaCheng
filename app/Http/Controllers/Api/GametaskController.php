<?php

namespace App\Http\Controllers\Api;

use App\Models\Gametaskreward;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GametaskController extends Controller
{
    //
    public function getTaskReward()
    {
        $taskReward = Gametaskreward::getAllReward();
        foreach($taskReward as $v){
            $v->uptime = date('Y-m-d H:i:s', $v->uptime);
        }
        return view('admin.gametask.gametasklist', compact('taskReward'));
    }

    public function setTaskReward(Request $request)
    {
        if($data = $request->all()){
            $id = $data['id'];
            $nums = $data['nums'];
            $res = Gametaskreward::setNum($id, $nums);
            if($res){
                return '修改成功';
            }else{
                return '修改失败';
            }
        }
    }

    public function setTaskMoney(Request $request)
    {
        if($data = $request->all()){
            $id = $data['id'];
            $money = $data['money'];
            $res = Gametaskreward::setMoney($id, $money);
            if($res){
                return '修改成功';
            }else{
                return '修改失败';
            }
        }
    }

    public function giveTaskReward(Request $request)
    {
        $reward = Gametaskreward::getAllReward()->toArray();
        if($reward){
            foreach($reward as $k => $v){
                array_pop($v);
                $reward[$k] = $v;
            }
            return [
                'status' => 1,
                'msg' => '获取游戏任务奖励设置成功',
                'data' => $reward
            ];
        }else{
            return [
                'status' => 0,
                'msg' => '网络连接失败'
            ];
        }

    }
}
