<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaketRequest extends FormRequest
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
            'price' => 'required|numeric',
            'duration' => 'required',
            'company_id' => 'required',
            'status' => 'required',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Waji di isi',
            'price.required' => 'harga Waji di isi',
            'price.numeric' => 'Input Harga Harus Angka',
            'company_id.required' => 'Perusahaan Wajib Dipilih',
            'status.required' => 'Status Wajib Dipilih',
            'type.required' => 'Tipe Paket Wajib Dipilih',
            
        ];
    }
}
