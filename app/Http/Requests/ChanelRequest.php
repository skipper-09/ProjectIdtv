<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChanelRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'categori_id' => 'required',
            'url' => 'required',
            'logo' => 'required||mimes:jpeg,jpg,png|required|max:8192',
        ];
    }
}