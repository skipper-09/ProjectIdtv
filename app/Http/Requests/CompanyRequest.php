<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'user_id' => 'required',
            'rekening' => 'required',
            'bank_name' => 'required',
            'owner_rek' => 'required',
            'fee_reseller' => 'required',
        ];
    }


    public function messages(){
        return[
            'name.required' => 'Nama Perusahaan Wajib Diisi',
            'address.required' => 'Alamat Perusahaan Wajib Diisi',
            'email' => 'Email Perusahaan Wajib Diisi',
            'phone.required' => 'Nomor Hp Perusahaan Wajib Diisi',
            'phone.integer'=> 'Hanya inputkan Nomor',
            'user_id.required' => 'Pemilik Perusahaan Wajib Diisi',
        ];
    }
}
