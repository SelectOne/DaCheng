<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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

    public function filter()
    {
        $arr = $this->all();
        $id= $this->has('id')? $this->get('id'): "";
        $search_type = $this->has('search_type')? $this->get('search_type'): "";
        switch ($search_type) {
            case 1:
                $arr['mid'] = $id;
                break;
            case 2:
                $arr['game_id'] = $id;
                break;
            case 3:
                $arr['sn'] = $id;
                break;
        }
        if ( ! array_key_exists('field', $arr) && ! array_key_exists('order', $arr) )
        {
            $arr['field'] = "order_id";
            $arr['order'] = "desc";
        }
        $arr['type'] = $this->has('type')? $this->get('type'): "";
        $arr['status'] = $this->has('status')? $this->get('status'): "";
        $time = $this->has('created_time')? $this->get('created_time'): "";
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
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
        return $arr;
    }
}
