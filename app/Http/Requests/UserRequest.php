<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
                'email' =>'required|email|unique:users,email,'.$this->id,
                'name'=>'required',
                'username'=>'required',
                'role'=>'required',
                'password' => 'nullable|min:6|confirmed',
                'password_confirmation' => 'same:password'
            ];
        }else {
            return [
                'email' => 'required|email|unique:users,email',
                'name'=>'required',
                'username'=>'required',
                'role'=>'required',
                'password' => 'required||min:6|confirmed',
                'password_confirmation' => 'same:password|required'
    
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib di isi.',
            'email.required' => 'Email wajib di isi.',
            'role.required' => 'Role wajib di isi.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib di isi.',
            'password.required' => 'Password Wajib di Isi',
            'password.min' => 'The password must be at least 6 characters long.',
            'password_confirmation.required'=>'Konfirmasi Password Wajib di Isi',
            'password_confirmation.same'=> 'Konfirmasi Password Tidak sama dengan Password',

        ];
    }
}
