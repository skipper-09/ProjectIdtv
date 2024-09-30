<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use Illuminate\Http\Request;

class ApichanelController extends Controller
{
    public function index(Request $request)
    {

        $id = $request->input('id');
        $name = $request->input('name');
        $stream = $request->input('url');

        if ($id) {
            $chanel = Chanel::with('categori')->find($id);
            if ($chanel) {
                return ResponseFormatter::success($chanel, 'Chanel berhasil diambil');
            } else {
                return ResponseFormatter::error($chanel, 'Data Chanel tidak ada', 400);
            }
        }

        $chanel = Chanel::with('categori');
        if ($name) {
            $chanel->where('name', 'like', '%' . $name . '%');
        }
        if ($stream) {
            $chanel->where('replacement_url', 'like', '%' . $name . '%');
        }
        return ResponseFormatter::success($chanel->get(), 'Chanel berhasil diambil');
    }

    public function category()
    {
        $categories = Categori::all();

        return ResponseFormatter::success($categories, 'Category retrieved successfully');
    }
}
