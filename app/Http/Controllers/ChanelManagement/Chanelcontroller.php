<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
        return view('pages.chanel.chanel-management.index', $data);
    }

    public function getData()
    {
        $chanel = Chanel::query();
        return DataTables::of($chanel)->addIndexColumn()->addColumn('action', function ($chanel) {
            $edit = ' <a href="' . route('chanel.edit', ['id' => $chanel->id]) . '" class="btn btn-sm btn-success action" data-id=' . $chanel->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            $delete = ' <button class="btn btn-sm btn-danger action" data-id=' . $chanel->id . ' data-type="delete" data-route="' . route('chanel.delete', ['id' => $chanel->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            return $edit . $delete;
        })->addColumn('logo', function ($chanel) {
            return '<img src="' . $chanel->logo . '" border="0" 
        width="40" class="img-rounded" align="center" />';
        })->rawColumns(['logo', 'action'])->make(true);
    }
}
