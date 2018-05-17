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
        $data = $this->model->with([
            'info' => function ($query) use($arr){
                $query->whereBetween('created_time', $arr['tt'],'and',$arr['not']);
            },
        ])
            ->offset($arr['offset'])
            ->limit($arr['limit'])
            ->orderBy($arr['field'], $arr['order']);

        if ( ! empty($card_id) ) {
            $data = $data->where('card.card_id', $card_id);
        }
        $data = $data->get();
//        dd($data);
        foreach ($data as &$v) {
            if ( ! is_null($v->info) ){
                $v['admin_name'] = $v->info->admin->admin_name;
                $v['ip'] = $v->info->admin->ip;
                $v['created_time'] = date("Y-m-d H:i:s" ,$v->info->created_time);
                $v['card_num'] = $v->info->card_num;
                $v['total_price'] = $v->info->total_price;
            }
            $v['card_name'] = $v->type->name;
            $v['card_price'] = $v->type->card_price;
            $v['given'] = $v->type->given;
        }

        return $data;
    }

    // 获取所有记录总数
    public function getCount($arr)
    {
        extract($arr);
        $count = Card::with([
            'info' => function ($query) use($tt, $not){
                $query->select()->whereBetween('created_time', $tt,'and',$not);
            },
        ]);
        
        if ( ! empty($card_id) ) {
            $count = $count->where('card.card_id', $card_id);
        }
        return $count->count();
    }

    // 生成实卡
    public function creatCard($data)
    {
        DB::transaction(function () use($data){
            $id = DB::table('card_info')->insert($data['info']);

            for ($i=1; $i<= $data['card_num']; $i++) {
                list($a,$b)=explode(' ', microtime());
                $unquid = substr(str_replace(".", "", $b.$a), 4, $data['card_length']-2);
                $card_id = $data['card_first'].$unquid;
                $password = substr(md5($unquid), '8', '10');
                DB::table('card')->insert([
                    'card_id' => $card_id.$i,
                    'type_id' => $data['type_id'],
                    'card_info_id' => $id,
                    'passwprd' > $password,
                ]);
            }
        });
    }

}