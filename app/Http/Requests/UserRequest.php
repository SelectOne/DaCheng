<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
        ];
    }

    public function messages(){
        return [
            'name.required'=>'名称不能为空',
        ];
    }

   /*public function attributes()
    {
        return [
            'name' => '姓名',
            'password' => '密码'
        ];
    }*/
}
