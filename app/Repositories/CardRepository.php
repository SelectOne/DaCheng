<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/14
 * Time: 17:44
 */

namespace App\Repositories;

use App\Models\Card;
use App\Repositories\Eloquent\Repository;
use DB;

class CardRepository extends Repository
{
    function model()
    {
        return "App\Models\Card";
    }

    // 分页
    public function limit($arr)
    {
        /*$data = $this->model->whereHas(
            'info', function ($query) use($arr){
                $query->whereBetween('created_time', $arr['tt'],'and',$arr['not']);
            }
        );*/
        $data = DB::table("card")
                    ->leftJoin("card_info as info", "info.id", "=" ,"card.card_info_id")
                    ->leftJoin("type", "type.id", "=" ,"info.type_id")
                    ->leftJoin("admin", "admin.admin_id", "=" ,"info.admin_id")
                    ->select("card.*","info.created_time","info.card_num","info.total_price","type.name","type.given","type.card_price","admin.admin_name","admin.ip")
                    ->whereBetween('info.created_time', $arr['tt'],'and',$arr['not']);

        if ( $arr['card_id'] ) {
            $data = $data ->where('card.card_id', $arr['card_id']);
        }
        $count = $data->count();
        $data = $data->offset($arr['offset'])->limit($arr['limit'])->orderBy($arr['field'], $arr['order'])->get()->toArray();
        foreach ($data as $v) {
            $v->created_time = date("Y-m-d H:i:s" ,$v->created_time);
        }
        $data['count'] = $count;
//        dd($data);
        return $data;
    }

    // 生成实卡
    public function creatCard($data)
    {
        DB::transaction(function () use($data){
            $id = DB::table('card_info')->insertGetId($data['info']);
            for ($i=1; $i<= $data['card_num']; $i++) {
                list($a,$b)=explode(' ', microtime());
                $u = rand(001, 999);
                $unquid = substr(str_replace(".", "", $b.$u.$a), 4, $data['card_length'])-1;
                $card_id = $data['card_first'].$unquid;
                $password = substr(md5($unquid), '8', '10');
                DB::table('card')->insert([
                    'card_id' => $card_id,
                    'card_info_id' => $id,
                    'password' => $password,
                ]);
            }
        });
    }

    /**
     * 删除实卡
     * @param $id
     */
    public function delete1($id)
    {
        DB::transaction(function () use($id){
            $row = Card::find($id);
            $info = DB::table("card_info as c")
                ->leftJoin("type as t", "t.id", "=", "c.type_id")
                ->where("c.id", $row->card_info_id)
                ->select("t.card_price", "c.card_num")
                ->first();
            DB::table("card_info")
                ->where("id", $row->card_info_id)
                ->update([
                    "card_num"    => $info->card_num - 1,
                    "total_price" => ($info->card_num - 1) * $info->card_price,
                ]);
            $row->delete();
        });
    }
}