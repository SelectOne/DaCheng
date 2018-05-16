<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/10
 * Time: 10:39
 */

namespace App\Repositories;

use  App\Repositories\Eloquent\Repository;

class RestrictRepository extends Repository
{

    function model()
    {
        return "App\Models\Restrict";
    }

    public function limit($arr)
    {

        extract($arr);
        $data = $this->model()::offset($offset)->limit($arr['limit'])->orderBy($field, $order);
        if ($type) {
            $data = $data->where('ip', 'like',"%$ip%");

        }else {
            if ($ip) {
                $data = $data->where('ip', $ip);
            }
        }
        $data = $data->get();

        foreach ($data as $v) {
            if ($v['limit_time'] == "0") {
                $v['limit_time'] = "永久禁止";
            } else {
                $v['limit_time'] = date("Y-m-d H:i:s", $v['limit_time']);
            }

            $v['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
        }
        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        extract($arr);
        if (!empty($type)) {
            $count = $this->model()::where('ip', 'like',"%$ip%")->count();
        }else {
            if ($ip) {
                $count = $this->model()::where('ip', $ip)->count();
            } else{
                $count = $this->model()::count();
            }
        }

        return $count;
    }
}