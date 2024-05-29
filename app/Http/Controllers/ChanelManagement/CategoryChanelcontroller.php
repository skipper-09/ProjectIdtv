<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriChanelRequest;
use App\Models\Categori;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-categori')) {
                $button .= ' <a href="' . route('categori-chanel.edit', ['id' => $categori->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $categori->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-categori')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $categori->id . ' data-type="delete" data-route="' . route('categori-chanel.delete', ['id' => $categori->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
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
        return redirect()->route('categori-chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Membuat Kategori!']);
    }

    public function show(Categori $categori, $id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Kategori',
            'categori' => $categori->find($id)
        ];
        return view('pages.chanel.categori.editcategori', $data);
    }

    public function update(CategoriChanelRequest $request, $id)
    {
        $request->validated();
        $categori = Categori::find($id);
        $categori->name = $request->name;
        $categori->save();
        return redirect()->route('categori-chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Kategori!']);
    }

    public function destroy($id)
    {
        Categori::where('id', $id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Kategori Berhasil Dihapus!.',
        ]);
    }
}
