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
        if ($this->isMethod('PUT')) {
            return [
                'name' => 'required',
                'categori_id' => 'required',
                'url' => 'required',
                'logo' => 'mimes:jpeg,jpg,png|max:8192',
            ];
        } else {
            return [
                'name' => 'required',
                'categori_id' => 'required',
                'url' => 'required|active_url',
                'logo' => 'mimes:jpeg,jpg,png|max:8192|required',
            ];
        }
    }

    // public function messages()
    // {
    //     return [
    //         'name.required' => 'The nama field is required.',
    //         'categori_id.required' => 'The nama field is required.',
    //         'url.required' => 'The nama field is required.',
    //         'url' => 'The nama field is required.',
    //         'name.required' => 'The nama field is required.',

    //     ];
    // }
}
