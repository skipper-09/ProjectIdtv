<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
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
            'phone' => 'required||numeric',
            'email' => 'required||unique:owners,email|email',
            'address' => 'required',
            'company_id' => 'required',
            'password' => 'required|min:6|',
            'password_confirmation' => 'required|same:password|confirmed',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The nama field is required.',
            'phone.required' => 'The phone field is required.',
            'phone.numeric' => 'Please only input number',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'address.required'=>'The address field is required.',
            'company_id.required'=>'The Company field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
