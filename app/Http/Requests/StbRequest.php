<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StbRequest extends FormRequest
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
            'name'=> 'required',
            'ram'=> 'required|integer|alpha_num',
            'internal'=> 'required|integer|alpha_num',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The nama field is required.',
            'ram.required' => 'The Ram field is required.',
            'ram.numeric' => 'Only input number',
            'internal.required' => 'The Internal field is required.',
            'internal.numeric' => 'Only input number',

        ];
    }
}
