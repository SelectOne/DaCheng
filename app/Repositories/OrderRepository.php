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
        $data = $this->model->whereBetween('created_time', $tt,'and',$not)
                              ->orderBy($field, $order)
                              ->offset($arr['offset'])
                              ->limit($arr['limit']);
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
        $data = $data->get();
        foreach ($data as $v) {
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
        }
        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        extract($arr);
        $count = $this->model->whereBetween('created_time', $tt,'and',$not);
        if ( isset($mid) ) {
            $count = $count->where('mid', $mid);
        }
        if ( isset($game_id)) {
            $count = $count->where('game_id', $game_id);
        }
        if ( isset($sn) ) {
            $count = $count->where('sn', $sn);
        }
        if ( $type != "") {
            $count = $count->where('type', $type);
        }
        if ( $status != "" ) {
            $count = $count->where('status', $status);
        }
        $count = $count->count();
        return $count;
    }
}