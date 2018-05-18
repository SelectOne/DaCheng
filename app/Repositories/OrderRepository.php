<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:42
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

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
        $data = $this->model->whereBetween('created_time', $tt,'and',$not);
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
        foreach ($data as $v) {
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
        }
        $data['count'] = $count;
        return $data;
    }

    public function amount()
    {
        $data = $this->model->where("status", 1)->sum('amount');

    }

}