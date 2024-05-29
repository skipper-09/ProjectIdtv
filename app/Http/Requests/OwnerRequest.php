<?php

namespace App\Http\Requests;

use App\Models\owner;
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
        $owner = $this->route('id');
        return [
            'name' => 'required',
            'phone' => 'required||numeric',
            'email' => 'required||email|unique:owners,email,' . $owner,
            'address' => 'required',
            'company_id' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
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
            'address.required' => 'The address field is required.',
            'company_id.required' => 'The Company field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
        ];
    }
}
