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

            $edit = ' <button class="btn btn-sm btn-success action" data-id=' . $categori->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></button>';
            $delete = ' <button class="btn btn-sm btn-danger action" data-id=' . $categori->id . ' data-type="delete"><i
                                                            class="fa-solid fa-trash"></i></button>';
            return $edit . $delete;
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

    public function show(Categori $categori)
    {
        $data = [
            'page_name' => 'Edit Tim',
            'categori' => $categori
        ];
        return view('pages.chanel.categori.editcategori', $data);
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
