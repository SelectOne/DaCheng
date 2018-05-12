<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestrictRequest extends FormRequest
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

    public function options(){
        $Method= $this->route()->getActionMethod();
        switch ($Method){
            case 'store':
                $data = $this->only([ 'ip', 'content', 'limit_login', 'limit_regist', 'limit_time', 'type']);
                $data['create_time'] = time();
                if (!is_null($data['limit_time'])) {
                    $data['limit_time'] = strtotime($this->get('limit_time'));
                } else {
                    $data['limit_time'] = 0;
                }
                return $data;
                break;
            case 'update':
                $data = $this->only([ 'id', 'ip', 'content', 'limit_login', 'limit_regist', 'limit_time', 'type']);
                if (!is_null($data['limit_login'])) {
                    $data['limit_login'] = $this->get('limit_login');
                } else {
                    unset($data['limit_login']);
                }

                if (!is_null($data['limit_regist'])) {
                    $data['limit_regist'] = $this->get('limit_regist');
                } else {
                    unset($data['limit_regist']);
                }

                if (!is_null($data['limit_time'])) {
                    $data['limit_time'] = strtotime($this->get('limit_time'));
                } else {
                    $data['limit_time'] = 0;
                }
                return $data;
                break;
        }
    }

    public function filter()
    {
        $data = $this->all();
        $data['ip'] = array_key_exists("limit_ip", $data)?$data['limit_ip']:"";
        $data['type'] = array_key_exists("type", $data)?$data['type']:"";
        $data['offset'] = ( $data['page']-1 ) * $data['limit'];
        return $data;
    }
}
