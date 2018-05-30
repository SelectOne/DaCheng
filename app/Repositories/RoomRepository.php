<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/21
 * Time: 16:30
 */

namespace App\Repositories;


use App\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class RoomRepository extends Repository
{

    function model()
    {
        return "App\Models\Room";
    }

    /*public function sum()
    {
        $sum = $this->model->sum("num");
        return $sum;
    }*/

    public function getAll()
    {
        $rows = DB::table('room')
                    ->leftJoin("coin_change as c", "c.room_id", "=", "room.id")
                    ->select("room.*", DB::raw("sum(change_coin) as num"))
                    ->groupBy("room.id", "room.name")
                    ->where("c.type", 1)
                    ->get();
//        dd($rows);
        return $rows;
    }
}