<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CurentStream;
use Illuminate\Http\Request;

class ApiCurentStream extends Controller
{
    public function Tambah(Request $request){

        $user = $request->user();

        CurentStream::create([
            'customer_id'=>$user->id,
            'device_id'=>$user->device_id,
            'device'=>$request->device,
            'device_brand'=>$request->device_brand,
            'device_model'=>$request->device_model,
            'chanel_id'=>$request->chanel_id,
        ]);

        return response()->json([
            'status'=>200,
            'message'=>'Success Menambahkan',
        ],200);

    }
}
