<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendapatanController extends Controller
{
    public function index()
    {
        $data = [

            'type_menu' => 'layout',
            'page_name' => 'Pendapatan',
        ];
        return view('pages.reseller.pendapatan.index', $data);
    }
}
