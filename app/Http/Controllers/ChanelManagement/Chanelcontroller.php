<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Chanelcontroller extends Controller
{
    public function index(): View
    {
        $data = [
            'chanel' => Chanel::all(),
            'type_menu' => 'layout',
            'page_name' => 'Chanel',
            'route' => 'chanel'
        ];
        dd($data);
        return view('pages.chanel.chanel-management.index', $data);
    }
}
