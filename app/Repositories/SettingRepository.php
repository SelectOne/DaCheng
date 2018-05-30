<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/28
 * Time: 10:42
 */

namespace App\Repositories;


use App\Models\CheckinReward;
use App\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class SettingRepository extends Repository
{

    function model()
    {
        return "App\Models\Setting";
    }

    public function first()
    {
        return $this->model->first();
    }

    public function update1($arr, $id)
    {
        $arr['param3'] = is_null($arr['param3']) ? 0 :$arr['param3'];
        $arr['param4'] = is_null($arr['param4']) ? 0 :$arr['param4'];

        DB::transaction(function () use($arr, $id) {
            foreach ($arr['reward'] as $key => $row)
            {
                $md = CheckinReward::find($key);
                $md->reward = $row;
                $md->created_time = time();
                $md->save();
            }
            unset($arr['reward']);
            $this->model->update($arr, $id);
        });
    }
}