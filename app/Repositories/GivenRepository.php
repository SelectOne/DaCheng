<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/22
 * Time: 15:26
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class GivenRepository extends Repository
{

    function model()
    {
        return "App\Models\Given";
    }

    public function getAll()
    {
        $data = $this->model->groupBy("type")->get([
            DB::raw("sum(num) as sum"), "type"
        ])->toArray();
        $arr = [];
        foreach ($data as $v)
        {
            $arr[] = $v['sum'];
        }
        return $arr;
    }
}