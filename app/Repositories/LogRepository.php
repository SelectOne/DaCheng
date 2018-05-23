<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/17
 * Time: 14:57
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;

class LogRepository extends Repository
{

    function model()
    {
        return "App\Models\Log";
    }

    public function limit($arr)
    {
        $data = $this->model->withCount('admin');
        if ( ! empty($arr['admin_id']) )
        {
            $data = $data->where('admin_id', $arr['admin_id']);
        }
        if ( ! empty($arr['type']) )
        {
            $data = $data->where('type', $arr['type']);
        }
        $count = $data->count();
        $data = $data->offset($arr['offset'])->limit($arr['limit'])->orderBy('id', 'desc')->get();
        foreach ( $data as $v)
        {
            $v['admin_name'] = $v->admin->admin_name;
            $v['ip'] = $v->admin->ip;
            $v['created_time'] = date("Y-m-d H:i:s", $v['created_time']);
        }
        $data['count'] = $count;

        return $data;
    }
}