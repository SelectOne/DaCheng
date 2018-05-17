<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeRequest extends FormRequest
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
        $Method = $this->route()->getActionMethod();
        switch ($Method) {
            case 'store':
                $arr = $this->only('name', 'card_price', 'given');
                return $arr;
            case 'update':
                $arr = $this->only('id', 'name', 'card_price', 'given');
                return $arr;
        }
    }
}
