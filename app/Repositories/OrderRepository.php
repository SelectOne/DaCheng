<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:42
 */

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Eloquent\Repository;
use DB;

class OrderRepository extends Repository
{
    function model()
    {
        return "App\Models\Order";
    }

    // 分页
    public function limit($arr)
    {
//        dd($arr);
        extract($arr);
        $data = $this->model->whereBetween('created_at', $tt,'and',$not);
        if ( isset($mid) ) {
            $data = $data->where('mid', $mid);
        }
        if ( isset($game_id)) {
            $data = $data->where('game_id', $game_id);
        }
        if ( isset($sn) ) {
            $data = $data->where('sn', $sn);
        }
        if ( $type != "") {
            $data = $data->where('type', $type);
        }
        if ( $status != "" ) {
            $data = $data->where('status', $status);
        }
        $count = $data->orderBy($field, $order)->offset($arr['offset'])->limit($arr['limit'])->count();
        $data = $data->get();
        /*foreach ($data as $v) {
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
        }*/
        $data['count'] = $count;
        return $data;
    }

    // 时间段充值数据
    public function amount($time)
    {
        if ($time['not']){
            $range = \Carbon\Carbon::now()->subDays(7);
            $data = Order::where('created_at', '>=', $range);
        } else {
            $data = Order::whereBetween('created_at', $time['tt'], 'and', $time['not']);
        }
        $data = $data->where('status', 1)
                     ->groupBy('date')
                     ->get([
                         DB::raw('Date(created_at) as date'),
                         DB::raw('sum(amount) as value')
                     ])->toArray();
        $arr = [];
        foreach ($data as $k=>$v)
        {
            $arr['date'][] = $v['date'];
            $arr['value'][] = (float)$v['value'];
//            $arr['total'] = array_sum($arr['value']);
        }
        return json_encode($arr);

    }

    // 统计充值人数
    public function rechargeNum($type = null)
    {
        $num = $this->model->where('status', 1);
        if (is_null($type)) {
            $num = $num->count();
        } else {
            $num = $num->groupBy('mid')->count();
        }
        return $num;
    }

    public function rechargeTop()
    {
//        $num = $this->model->where('status', 1)->select(DB::raw('max(amount) as top'),'created_at')->groupBy('order_id')->first();


    }

    public function type()
    {
//        $num = $this->model->where('status', 1);
    }

    // 充值总金额
    public function total()
    {
        $total = $this->model->where("status", 1)->sum("paid");
        return $total;
    }
}