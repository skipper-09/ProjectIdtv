<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\MidtransSetting;
use Illuminate\Http\Request;

class MidtransSettingController extends Controller
{

    public function index(){
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Setting Midtrans',
            'midtrans'=>MidtransSetting::first(),
        ];
        return view('pages.settings.midtrans.index',$data);
    }

    public function update(Request $request){

       $request->validate([
        'client_key'=>'required',
        'server_key'=>'required',
        'url' => 'required|url',
        'production'=>'required',
       ],[
        'client_key.required' => 'Client Key harus diisi.',
        'server_key.required' => 'Server Key wajib diisi.',
        'url.required' => 'URL wajib diisi.',
        'url.url' => 'URL harus dalam format yang benar.',
        'production.required' => 'Production status harus dipilih.',
       ]);
        MidtransSetting::updateOrCreate( ['id' => optional(MidtransSetting::first())->id],$request->all());
        return redirect()->back()->with(['status' => 'Success!', 'message' => 'Berhasil Setting Payment Gateway!']);
    }
}
