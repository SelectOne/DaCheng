<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoinChangeRequest extends FormRequest
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
        $data = $this->all();
        $data['id'] = array_key_exists("id", $data)?$data['id']:"";
        $data['type'] = array_key_exists("type", $data)?$data['type']:"";
        $data['offset'] = ( $data['page']-1 ) * $data['limit'];
        $time = $this->get("created_at", "");
        $data['not'] = true;
        if ($time) {
            $arr = explode(" -- ", $time);
            $data['tt'] = [$arr[0], $arr[1]];
            $data['not'] = false;
        } else {
            $data['tt'] = ["", ""];
        }
        return $data;
    }
}
