<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'password' => 'required',
            'captcha' => 'required|captcha'
        ];
    }

    /*public function messages(){
        return [
            'name.required'=>'名称不能为空',
        ];
    }*/

   public function attributes()
    {
        return [
            'name' => '用户名',
            'password' => '密码',
            'captcha' => '验证码'
        ];
    }
}
