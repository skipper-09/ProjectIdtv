<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use Illuminate\Http\Request;

class ApichanelController extends Controller
{
    public function index()
    {
        $chanel = Chanel::with('categori')->get();
        return response()->json($chanel);
    }
}
