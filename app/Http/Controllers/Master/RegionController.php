<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegionRequest;
use App\Models\Region;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RegionController extends Controller
{
    public function index()
    {
        $data = [
            'categori' => Region::all(),
            'type_menu' => 'master',
            'page_name' => 'Area',

        ];
        return view('pages.master.region.index', $data);
    }

    public function getData()
    {
        $data = Region::query();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-categori')) {
                $button .= ' <a href="' . route('region.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-categori')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('region.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => 'master',
            'page_name' => 'Tambah Area',

        ];
        return view('pages.master.region.addregion', $data);
    }

    public function store(RegionRequest $request)
    {
        $data = $request->validated();
        Region::create($data);
        return redirect()->route('region')->with(['status' => 'Success!', 'message' => 'Berhasil Membuat Area!']);
    }

    public function show(Region $region, $id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Area',
            'region' => $region->find($id)
        ];
        return view('pages.master.region.editregion', $data);
    }

    public function update(RegionRequest $request, $id)
    {
        $request->validated();
        $region = Region::find($id);
        $region->name = $request->name;
        $region->save();
        return redirect()->route('region')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Area!']);
    }

    public function destroy($id)
    {
        try {
            Region::where('id', $id)->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Kategori Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Area Tidak Bisa Dihapus Karena Masih digunakan oleh Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
