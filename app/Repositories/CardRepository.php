<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:44
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class CardRepository extends Repository
{
    function model()
    {
        return "App\Models\Card";
    }

    // 分页
    public function limit($arr)
    {
//        dd($arr);
        extract($arr);
        $data = $this->model()::whereBetween('created_time', $tt,'and',$not)
            ->offset($arr['offset'])
            ->limit($arr['limit']);
        if ( ! empty($card_id) ) {
            $data = $data->where('card_id', $card_id);
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
        if ( ! empty($card_id) ) {
            $count = $this->model()::where('card_id', $card_id)->whereBetween('created_time', $tt,'and',$not)->count();
        } else{
            $count = $this->model()::whereBetween('created_time', $tt,'and',$not)->count();
        }
        return $count;
    }
}