<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryChanelcontroller extends Controller
{
    public function index() : View {
        $data = [
            'categori' => Categori::all(),
            'type_menu' => 'layout',
            'page_name' => 'Kategori'
        ];
          return view('pages.chanel.categori.index',$data);
    }
}
