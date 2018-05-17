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
        $data = $this->model->orderBy($arr['field'], $arr['order'])->offset($arr['offset'])->limit($arr['limit'])->get();
        foreach ($data as &$v) {
            foreach ($v->card as $item) {
                $v['card_name'] = $item->type->name;
                $v['given'] = $item->type->given;
            }
            $v['used'] = $v->card->where('is_used', 1)->count();
            $v['not_used'] = $v->card->where('is_used', 0)->count();
            $v['expire'] = $v->card->where('expire_time', '<', time())->count();
            $v['total_given'] = $v['card_num'] * $v['given'];
        }
        return $data;
    }

    public function getCount()
    {
        $count = $this->model->count();
        return $count;
    }
}