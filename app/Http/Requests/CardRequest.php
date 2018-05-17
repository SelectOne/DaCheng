<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function options()
    {
        $Method= $this->route()->getActionMethod();
        switch ($Method) {
            case 'store':
                $data = $this->only(['type_id', 'card_price', 'card_num', 'given', 'expire_time', 'max_use', 'card_first', 'card_length']);
                $data['info'] = [
                    'admin_id'     => session("admin_id"),
                    'card_num'     => $data['card_price'],
                    'given'        => $data['given'],
                    'total_price'  => $data['card_num'] * $data['card_price'],
                    'max_use'      => $data['max_use'],
                    'created_time' => time(),
                    'expire_time'  => strtotime($data['expire_time']),
                ];
                return $data;
                break;
            case 'update':
                break;
        }
    }

    public function filter()
    {
        $arr = $this->all();
        $arr['card_id'] = $this->has("id")?$this->get("id"):"";
        $time = $this->has("created_time")?$this->get("created_time"):"";
        $arr['not'] = true;
        if($time){
            $tt = explode(' -- ',$time);
            $startime=strtotime("{$tt[0]} 00:00:01");
            $endtime=strtotime("{$tt[1]} 23:59:59");
            $arr['tt'] = [$startime,$endtime];
            $arr['not']  = false;
        }else{
            $arr['tt'] = ['',''];
        }
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
        return $arr;
    }
}