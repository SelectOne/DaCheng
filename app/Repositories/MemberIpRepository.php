<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/30
 * Time: 16:05
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;

class MemberIpRepository extends Repository
{

    function model()
    {
        return "App\Models\MemberIp";
    }


    public function limit_member($arr)
    {
        $data = $this->model;
        if ( ! is_null($arr['ip']) )
        {
            $data = $data->where("ip", $arr['ip']);
        }
        if ( ! is_null($arr['machine_ip']))
        {
            $data = $data->where("machine_ip", $arr['machine_ip']);
        }
        $count = $data->count();
        $data = $data->skip($arr['offset'])->take($arr['limit'])->get();
        $data['count'] = $count;
//        dd($data);
        return $data;

    }
}