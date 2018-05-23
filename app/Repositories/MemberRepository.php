<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/7
 * Time: 10:34
 */

namespace App\Repositories;

use App\Models\CoinChange;
use App\Models\Given;
use App\Models\Member;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Eloquent\Repository;
use DB;

class MemberRepository extends Repository
{

    function model()
    {
        return 'App\Models\Member';
    }

    // 分页查询
    public function limit($arr)
    {
//        dd($arr);
        $id = array_key_exists("id", $arr)?$arr['id']:"";
        $pid = array_key_exists("pid", $arr)?$arr['pid']:"";
        $nickname = array_key_exists("nickname", $arr)?$arr['nickname']:"";
        $time = array_key_exists("time", $arr)?$arr['time']:"";
        $ip = array_key_exists("ip", $arr)?$arr['ip']:"";
        $machine_ip = array_key_exists("machine_ip", $arr)?$arr['machine_ip']:"";

        $not = true;
        if($time){
            $tt = explode(' -- ',$time);
            $startime=strtotime("{$tt[0]} 00:00:01");
            $endtime=strtotime("{$tt[1]} 23:59:59");
            $tt = [$startime,$endtime];
            $not = false;
        }else{
            $tt = ['',''];
        }
        $offset = ( $arr['page']-1 ) * $arr['limit'];
        if (array_key_exists('field', $arr) && array_key_exists('order', $arr))
        {
            $field = $arr['field'];
            $type  = $arr['order'];
        } else {
            $field = 'id';
            $type  = 'desc';
        }
        $data = $this->model->where('nickname', 'like', "$nickname%")
                        ->whereBetween('login_time', $tt,'and',$not);
        if (!empty($id)) {
            $data = $data->where('id', $id);
        }
        if (!empty($pid)) {
            $data = $data->where('pid', $pid);
        }
        if (!empty($ip)) {
            $data = $data->where('ip', $ip);
        }
        if (!empty($machine_ip)) {
            $data = $data->where('machine_ip', $machine_ip);
        }
        $count = $data->count();
        $data = $data->offset($offset)->limit($arr['limit'])->orderBy($field, $type)->get();
        $data['count'] = $count;
        return $data;
    }

    /**
     * 后台充值
     * @param $id  玩家ID
     * @param $num  充值金额
     */
    public function recharge($id, $num)
    {
        DB::transaction( function () use( $id, $num )
        {
            // 当前充值玩家
            $row = Member::find($id);
            // 插入玩家金币变化表
            CoinChange::create([
                "mid"         => $id,
                "start_coin"  => $row->num,
                "change_coin" => $num,
                "end_coin"    => $row->num + $num,
                "type"        => 5,
            ]);
            // 玩家表金币增长
            $row->num = $row->num + $num;
            $row->save();
            // 插入赠送金币表
            Given::create([
                "mid"  => $id,
                "num"  => $num,
                "type" => 2,
            ]);
        });
    }

    public function register($time)
    {
        if ($time['not']){
            $range = \Carbon\Carbon::now()->subDays(7);
            $data = Member::where('created_at', '>=', $range);
        } else {
            $data = Member::whereBetween('created_at', $time['tt'], 'and', $time['not']);
        }
        $data = $data->groupBy('date')
                     ->get([
                         DB::raw('Date(created_at) as date'),
                         DB::raw('count(id) as value')
                     ])->toArray();
        $arr = [];
        foreach ($data as $k=>$v)
        {
            $arr['date'][] = $v['date'];
            $arr['value'][] = $v['value'];
        }

        return json_encode($arr);
    }

    public function total($status = null)
    {
        if (is_null($status)) {
            $num = $this->model->count();
        } else {
            $num = $this->model->where('status', 0)->count();
        }

        return $num;
    }

    // 在房间玩家信息
    public function mInRoom($arr)
    {
        $offset = ( $arr['page']-1 ) * $arr['limit'];
        $room_id = array_key_exists("room_id", $arr)?$arr['room_id']:"";
        $data = $this->model->where('status', 0);
        if ( ! empty($room_id) ) {
            $data = $data->where("room_id", $room_id);
        }
        $count = $data->count();
        $data = $data->offset($offset)->limit($arr['limit'])->get();
        foreach ($data as $v)
        {
            $v['room_name'] = $v->room->name;
        }
        $data['count'] = $count;
        return $data;
    }

    // 每天活跃时长大于1小时玩家数
    public function lively1($time)
    {
        if ($time['not']) {
            $range = \Carbon\Carbon::now()->subDays(30);
            $data = Member::where('login_time', '>=', $range);
        } else {
            $data = Member::whereBetween('login_time', $time['tt'], 'and', $time['not']);
        }
        $data = $data->where([
                                ['status', '=', 0],
                                ["duration", '>=', 1]
                            ])
                            ->groupBy('date')
                            ->get([
                                DB::raw('Date(login_time) as date'),
                                DB::raw('count(id) as value')
                            ])
                            ->toArray();
        $arr = [];
        foreach ($data as $k=>$v)
        {
            $arr['date'][] = $v['date'];
            $arr['value'][] = $v['value'];
        }
        return json_encode($arr);
    }

    // 每月活跃时长玩家数
    public function lively2()
    {
        $range = \Carbon\Carbon::now()->subDays(30);
        $num0 = Member::where('login_time', '>=', $range)
            ->where('status', 0)
            ->where([
                ["duration", '<', 1]
            ])
            ->get([
                DB::raw('count(id) as value')
            ])
            ->toArray();
        $arr[0] = $num0[0]['value'];

        $num1 = Member::where('login_time', '>=', $range)
                        ->where('status', 0)
                        ->where([
                            ["duration", '>=', 1],
                            ["duration", '<', 40]
                        ])
                        ->get([
                            DB::raw('count(id) as value')
                        ])
                        ->toArray();
        $arr[1] = $num1[0]['value'];

        $num2 = Member::where('login_time', '>=', $range)
                        ->where('status', 0)
                        ->where([
                            ["duration", '>=', 40]
                        ])
                        ->get([
                            DB::raw('count(id) as value')
                        ])
                        ->toArray();
        $arr[2] = $num2[0]['value'];
        return $arr;
    }


}