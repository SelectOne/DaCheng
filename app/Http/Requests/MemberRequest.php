<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'num' => 'required|regex:[^\d+$]',
        ];
    }

    public function messages(){
        return [
            'num.required' => '充值不能为空!',
            'num.regex'    => '充值必须是数字!',
        ];
    }

    /*public function options(){
        $Method= $this->route()->getActionMethod();
        switch ($Method){
            case 'store':
                return $this->only(['username','real_name','password','salts','phone','position','authority_id','is_delete','entry_time']);
                break;
            case 'update':
                return $this->only(['username','real_name','password','salts','phone','position','authority_id','is_delete','entry_time']);
                break;
        }
    }*/

    public function filter()
    {
        $arr = $this->all();
        $arr['id'] = array_key_exists("id", $arr)?:"";
        $arr['pid'] = array_key_exists("pid", $arr)?:"";
        $arr['nickname'] = array_key_exists("nickname", $arr)?:"";
        $time = array_key_exists("time", $arr)?:"";
        $arr['ip'] = array_key_exists("ip", $arr)?:"";
        $arr['machine_ip'] = array_key_exists("machine_ip", $arr)?:"";
        $not = true;
        if($time){
            $tt = explode(' -- ',$time);
            $startime=strtotime("{$tt[0]} 00:00:01");
            $endtime=strtotime("{$tt[1]} 23:59:59");
            $tt = [$startime,$endtime];
            $not = false;
        }else{
            $tt = ['',''];
        }
        return $arr;
    }

}
