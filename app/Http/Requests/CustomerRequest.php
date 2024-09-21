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
                'nik' => 'required',
                'mac' => 'required',
                'username' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'paket_id' => 'required',
                'stb_id' => 'required',
                'region_id' => 'required',
                'password' => '',
                'password_confirmation' => 'same:password'
            ];
        } else {
            return [
                'name' => 'required',
                'nik' => 'required',
                'mac' => 'required',
                'username' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'paket_id' => 'required',
                'stb_id' => 'required',
                'region_id' => 'required',
                'password' => 'required||min:6',
                'password_confirmation' => 'same:password|required'
            ];
        }
    }
    public function messages()
    {
        return [
            'name.required' => 'Nama Wajib di Isi',
            'nik.required' => 'Nik Wajib di Isi',
            'mac.required' => 'Mac Wajib di Isi',
            'username.required' => 'Username Wajib di Isi',
            'password.required' => 'Password Wajib di Isi',
            'password.min' => 'The password must be at least 6 characters long.',
        ];
    }
}
