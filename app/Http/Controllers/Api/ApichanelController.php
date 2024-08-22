<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use Illuminate\Http\Request;

class ApichanelController extends Controller
{
    public function index()
    {
        $chanel = Chanel::with('categori')->get();
        return response()->json($chanel);
    }

    public function category()
    {
        $categories = Categori::with('chanel')->get();
        return response()->json($categories);
    }
}
