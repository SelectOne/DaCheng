<?php

namespace App\Http\Controllers\Api;

use App\Models\Checkin;
use App\Models\Checkreward;
use App\Models\Lowincome;
use App\Models\Member;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function giveCheckDay(Request $request)
    {
        //dd(date('Y-m-d H:i:s', 1505446692));
        $data = $request->all();
        if(isset($data['mid']) && !empty($data['mid'])){
            $user_id = $data['mid'];
            if(!Member::isHasUserid($user_id))
                return [
                    'status' => 0,
                    'msg' => '此用户id不存在'
                ];
            $isFirstCheckin = Checkin::isHaveId($user_id);
            //dd($isFirstCheckin);
            if(!$isFirstCheckin){
                //当前状态为从未签到过    //,或者七天已经签满归零一轮签到结束
                $arr = [
                    'status' => 1,
                    'msg' => '可签到',
                    'data' => 1     //当前可以签到的天数为第一天
                ];
            }else{
                //表示之前签到过
                $days = $isFirstCheckin->checkin_days;
                $lastTime = $isFirstCheckin->last_checkin_time;
                //判断当天是否已签到
                $isCurrentDay = date('Y-m-d', $lastTime);
                //dd($days, $isCurrentDay, date('Y-m-d', time()), time());
                if($isCurrentDay == date('Y-m-d', time())){
                    //当天已签到
                    $arr = [
                        'status' => 0,
                        'msg' => '不能签到，当天已签到',
                        'data' => $days
                    ];
                }else{
                    //当天未签到
                    if($days != 7){
                        $checkinDay = $days + 1;
                        $arr = [
                            'status' => 1,
                            'msg' => '可以签到',
                            'data' => $checkinDay   //当前可以签到的天数，即第$checkinDay天
                        ];
                    }else{
                        $arr = [
                            'status' => 1,
                            'msg' => '可以签到，上回已签满七天，此次签到次数为第1天',
                            'data' => 1
                        ];
                    }
                }
            }
        }else{
            $arr = [
                'status' => 0,
                'msg' => '参数错误'
            ];
        }

        return $arr;
    }

    public function checkIn(Request $request)
    {
        //dd(1);
        $data = $request->all();
        if(isset($data['mid']) && !empty($data['mid'])){
            $user_id = $data['mid'];

            if(!Member::isHasUserid($user_id))
                return [
                    'status' => 3,
                    'msg' => '此用户id不存在'
                ];
            //$day = $data['day'];
            //dd($user_id, $day);
            $reward = Checkreward::getRewardByUid($user_id);
            //return $reward;
            $arr = [
                'mid' => $user_id,
                'gid' => 1,
                'num' => $reward,
                'type' => 7,
                'created_at' => time()
            ];
            //return $arr;
            //dd($arr);
            DB::beginTransaction();
            //return $arr;
            $result = inlog_updmem_notransc($arr);
            $isFirstCheckin = Checkin::isHaveId($user_id);
            //从未签到过,此时是insert
            if(!$isFirstCheckin){
                $res = Checkin::insertCheckLog($user_id, 1);
            }else{
                //签到过，此时是更新
                $res = Checkin::updateCheckLog($user_id);
            }
            if(!$result || !$res){
                DB::rollBack();
                return [
                    'status' => 0,
                    'msg' => '签到失败，请稍后再试'
                ];
            }else{
                DB::commit();
                return [
                    'status' => 1,
                    'msg' => '签到成功',
                    'data' => ['money' => $result, 'days' => $res]
                ];
            }
        }else{
            return [
                'status' => 2,
                'msg' => '参数错误'
            ];
        }

    }

    //签到奖励
    public function chReward(Request $request)
    {
        if($request->input()){
            $days = $request->input('days');
            $reward = $request->input('reward');
            //$res = Checkreward::changeReward($days, $reward);
            $res = DB::table('checkin_reward')
                ->where('days', $days)
                ->update(['reward' => $reward]);
            if($res){
                return '修改成功';
            }else{
                return '数据未修改';
            }
        }else{
            $res = Checkreward::getAllReward();
            $lowConfig = Lowincome::getConfig();
            return view('admin.checkin.checkinlist', compact('res', 'lowConfig'));
        }
    }

    public function giveCheckReward()
    {
        $allReward = Checkreward::getAllReward();
        if($allReward){
            return [
                'status' => 1,
                'msg' => '获取信息成功',
                'data' => $allReward
            ];
        }else{
            return [
                'status' => 0,
                'msg' => '获取数据失败'
            ];
        }
    }

    public function test()
    {
        dd(Member::isHasUserid(1010));
        //dd();
        /*dd(Checkreward::chReward(3, 1000));
        DB::connection()->enableQueryLog();
        $a = DB::table('checkin_reward')
            ->where('days', 3)
            ->update(['reward' => 111211]);
        $log = DB::getQueryLog();
        dd($log);   //打印sql语句
        dd($a);*/
        //dd(Checkreward::getReward(3));

        //dd(1);
        /*$arr = [
            'mid' => 2089,
            'gid' => 1,
            'num' => 2111,
            'type' => 1,
            'created_at' => time()
        ];
        if($a = inlog_updmem($arr)){
            dd($a);
        }else{
            echo 44;
        }*/
    }
}
