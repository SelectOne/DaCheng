<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/16
 * Time: 14:09
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class TypeRepository extends Repository
{

    function model()
    {
        return "App\Models\Type";
    }

    public function limit($arr)
    {
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
        if ( ! array_key_exists('field', $arr) && ! array_key_exists('order', $arr) )
        {
            $arr['field'] = "id";
            $arr['order'] = "desc";
        }
        $count = $this->model->count();
        $data = $this->model->orderBy($arr['field'], $arr['order'])->offset($arr['offset'])->limit($arr['limit'])->get();
        $data['count'] = $count;
        return $data;
    }
}