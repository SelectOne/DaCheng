<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogRequest extends FormRequest
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
        $arr['admin_id'] = $this->has("admin_id")?$this->get("admin_id"):"";
        $arr['type'] = $this->has("type")?$this->get("type"):"";
        $arr['offset'] = ( $arr['page']-1 ) * $arr['limit'];
        return $arr;
    }
}
