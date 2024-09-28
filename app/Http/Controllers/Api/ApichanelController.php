<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use Illuminate\Http\Request;

class ApichanelController extends Controller
{
    public function index()
    {
        $chanel = Chanel::with('categori')->get();
       return ResponseFormatter::success($chanel, 'Chanel retrieved successfully');
    }

    public function category()
    {
        $categories = Categori::with('chanel')->get();
        
        return ResponseFormatter::success($categories, 'Category retrieved successfully');
    }
}
