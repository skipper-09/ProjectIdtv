<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
                'ppoe' => 'required',
                'mac' => 'required',
                'username' => 'required',
                'password' => '',
                'password_confirmation' => 'same:password'
            ];
        } else {
            return [
                'name' => 'required',
                'ppoe' => 'required',
                'mac' => 'required',
                'username' => 'required',
                'password' => 'required||min:6',
                'password_confirmation' => 'same:password|required'
            ];
        }
    }
    public function messages()
    {
        return [
            'name.required' => 'The nama field is required.',
            'ppoe.required' => 'The ppoe field is required.',
            'mac.required' => 'The mac field is required.',
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
        ];
    }
}
