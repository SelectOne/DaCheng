<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/22
 * Time: 16:37
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;

class CoinChangeRepository extends Repository
{

    function model()
    {
        return "App\Models\CoinChange";
    }

    public function limit($arr)
    {
        $data = $this->model->whereBetween('created_at', $arr['tt'], 'and', $arr['not']);
        if ( ! empty($arr['id']) )
        {
            $data = $data->where('mid', $arr['id']);
        }
        if ( ! empty($arr['type']) )
        {
            $data = $data->where('type', $arr['type']);
        }
        $count = $data->count();

        $data = $data->offset($arr['offset'])->limit($arr['limit'])->orderBy('id', 'desc')->get();
        foreach ($data as $v)
        {
            switch ($v['type'])
            {
                case 1:
                    $v['type'] = "游戏";
                    break;
                case 2:
                    $v['type'] = "在线充值";
                    break;
                case 3:
                    $v['type'] = "实卡充值";
                    break;
                case 4:
                    $v['type'] = "注册赠送";
                    break;
                case 5:
                    $v['type'] = "后台赠送";
                    break;
                case 6:
                    $v['type'] = "任务赠送";
                    break;
                case 7:
                    $v['type'] = "签到赠送";
                    break;
            }
        }
        $data['count'] = $count;
//        dd($data);
        return $data;
    }

    // 平台游戏金币总输赢
    public function rommSum()
    {
        $sum = $this->model->where("type", 1)->sum("change_coin");
        return $sum;
    }
}