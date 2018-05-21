<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/7
 * Time: 10:34
 */

namespace App\Repositories;

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
//        dd($field, $type);
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
//        dd($field, $type);
        $data = $data->offset($offset)->limit($arr['limit'])->orderBy($field, $type)->get();
        foreach ($data as $v) {
            $v['login_time'] = date("Y-m-d H:i:s", $v['login_time']);

        }
        $data['count'] = $count;
        return $data;
    }

    // 充值
    public function recharge($id, $num)
    {
        $rs = $this->model()::where("id", $id)->increment('num', $num);
        return $rs;
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
//            $arr['total'] = array_sum($arr['value']);
        }
//        dd($arr);
        return json_encode($arr);
    }

    public function total()
    {
        $num = $this->model->count();
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
}