<?php

namespace App\Http\Controllers\Api;

use App\Models\Lowincome;
use App\Models\Lowmoney;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LowincomeController extends Controller
{
    //
    public function setLowincomeConfig(Request $request)
    {
        if($request->input()){
            $field = $request->input('field');
            $val = $request->input('val');
            $upResult = Lowincome::setConfig($field, $val);
            if($upResult){
                return '修改成功';
            }else{
                return '修改失败';
            }
        }
    }

    //点击领取低保
    public function clickLowincome(Request $request)
    {
        /*dd(Lowmoney::isCanClick(2072));
        foreach($a = Lowmoney::isCanClick(2) as $v){
            $v->gettime = date('Y-m-d H:i:s', $v->gettime);
        }
        dd($a);
        dd(Lowmoney::isCanClick(2));*/
        $data = $request->all();
        if(isset($data['user_id'])){
            $user_id =  $data['user_id'];
            $result = Lowmoney::isCanClick($user_id);
            //dd($result);
            if($result === 1){
                return [
                    'status' => 1,
                    'msg' => '您的金币数不满足领取低保条件！'
                ];
            }elseif($result === 2 || $result === 3 || $result === 6){
                DB::beginTransaction();
                $arr = [
                    'mid' => $user_id,
                    'gid' => 1,
                    'num' => Lowmoney::getLowReward(),
                    'type' => 8,
                    'created_at' => time()
                ];
                $in_up_res = inlog_updmem_notransc($arr);
                $in_low = Lowmoney::insertLowLog($user_id);
                //dd($in_low);
                if($in_up_res && $in_low){
                    DB::commit();
                    return [
                        'status' => 2,
                        'msg' => '领取成功',
                        'data' => $in_up_res
                    ];
                }else{
                    DB::rollBack();
                    return [
                        'status' => 5,
                        'msg' => '网络异常，领取失败'
                    ];
                }
            }elseif($result === 4){
                return [
                    'status' => 3,
                    'msg' => '用户id错误，无此用户'
                ];
            }elseif($result === 5){
                return [
                    'status' => 4,
                    'msg' => '当天已领取5次，不能再领取'
                ];
            }
        }else{
            return [
                'status' => 0,
                'msg' => '参数错误'
            ];
        }
    }

    //低保设置接口
    public function getLowConfig()
    {
        $lowConfig = Lowincome::getConfig();
        $lowConfig = $lowConfig->toArray();
        array_shift($lowConfig);
        if($lowConfig){
            $arr = [
                'status' => 1,
                'msg' => 'ok',
                'data' => $lowConfig
            ];
        }else{
            $arr = [
                'status' => 0,
                'msg' => '网络异常，获取信息失败'
            ];
        }
        return $arr;
    }
}
