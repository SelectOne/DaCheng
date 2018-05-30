<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2018/5/16
 * Time: 16:41
 */

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;
use function foo\func;
use Illuminate\Support\Facades\DB;

class CardInfoRepository extends Repository
{

    function model()
    {
        return "App\Models\CardInfo";
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
        /*$data = $this->model->with('card','type')->orderBy($arr['field'], $arr['order'])->offset($arr['offset'])->limit($arr['limit'])->get();
        foreach ($data as &$v) {
            $v['card_name'] = $v->type->name;
            $v['given'] = $v->type->given;
            $v['used'] = $v->card->where('is_used', 1)->count();
            $v['not_used'] = $v->card->where('is_used', 0)->count();
            $v['expire'] = $v->card->where('expire_time', '<', time())->count();
            $v['total_given'] = $v['card_num'] * $v['given'];
        }*/

        $data = DB::table("card_info as i")
                    ->leftJoin("card as c", "c.card_info_id", "=", "i.id")
                    ->leftJoin("type as t", "t.id", "=", "i.type_id")
                    ->groupBy("i.type_id", "t.name", "i.total_price")
                    ->where("c.is_used", 0)
                    ->offset($arr['offset'])
                    ->limit($arr['limit'])
                    ->get([
                        "t.name as card_name",
                        DB::raw("sum(gm_i.total_price) as total_price"),
                        DB::raw("count(gm_i.card_num) as card_num"),
                        DB::raw("count(gm_c.id) as not_used")
                    ]);
        $data['count'] = $count;
        return $data;
    }

    public function limit1($arr)
    {
//        dd($arr);
        $data = $this->model->with('card')->whereBetween('created_time', $arr['tt'], 'and', $arr['not'])->get();
        dd($data);
        $count = $data->count();
        $data= $data->skip($arr['offset'])->take($arr['limit'])->orderBy($arr['field'], $arr['order'])->get();

        foreach ($data as $v) {
            $v = $v->card;
            dd($v);
            /*foreach ($data as $value)
            {
                $value['total_price'] = $v['total_price'];
            }*/
        }
        $data['count'] = $count;
//        dd($data);
        return $data;
    }
}