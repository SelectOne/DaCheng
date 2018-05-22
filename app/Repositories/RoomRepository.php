<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/21
 * Time: 16:30
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;

class RoomRepository extends Repository
{

    function model()
    {
        return "App\Models\Room";
    }

    public function sum()
    {
        $sum = $this->model->sum("num");
        return $sum;
    }

    public function getAll()
    {
        $rows = $this->model->get();
        return $rows;
    }
}