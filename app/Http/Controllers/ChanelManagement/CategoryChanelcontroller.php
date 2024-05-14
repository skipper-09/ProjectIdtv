<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriChanelRequest;
use App\Models\Categori;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;


class CategoryChanelcontroller extends Controller
{
    public function index(): View
    {
        $data = [
            'categori' => Categori::all(),
            'type_menu' => 'layout',
            'page_name' => 'Kategori',

        ];
        return view('pages.chanel.categori.index', $data);
    }

    public function getData()
    {
        $categori = Categori::query();
        return DataTables::of($categori)->addIndexColumn()->addColumn('action', function ($categori) {
            return '<div>
            <button class="btn btn-sm btn-success" data-toggle="tooltip"
                                                        data-placement="left" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></button>
                                                    <button class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                        data-placement="bottom" title="Hapus Data"><i
                                                            class="fa-solid fa-trash"></i></button>
            </div>';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Kategori',

        ];
        return view('pages.chanel.categori.addcategori', $data);
    }

    public function store(CategoriChanelRequest $request)
    {
        $data = $request->validated();
        Categori::create($data);
        return redirect()->route('categori-chanel');
    }

    public function destroy()
    {
    }
}
