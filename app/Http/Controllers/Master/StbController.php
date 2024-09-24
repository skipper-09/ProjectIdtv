<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StbRequest;
use App\Models\Customer;
use App\Models\Stb;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StbController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'master',
            'page_name' => 'Stb',
        ];
        return view('pages.master.stb.index', $data);
    }


    public function getData()
    {
        $data = Stb::query()->orderByDesc('id');
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-stb')) {
                $button .= ' <a href="' . route('stb.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-stb')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('stb.delete', ['id' => $data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('ram', function ($data) {
            return $data->ram. ' GB';
        })->editColumn('internal', function ($data) {
            return $data->internal. ' GB';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => 'master',
            'page_name' => 'Tambah Stb',

        ];
        return view('pages.master.stb.addstb', $data);
    }

    
    public function store(StbRequest $request)
    {
        $data = $request->validated();
        Stb::create($data);
        return redirect()->route('stb')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Stb!']);
    }


    public function show(Stb $stb, $id)
    {
        
        $data = [
            'type_menu' => 'master',
            'page_name' => 'Edit Stb',
            'stb' => $stb->find($id)
        ];
        return view('pages.master.stb.editstb', $data);
    }

    public function update(StbRequest $request, $id)
    {
        $request->validated();
        $stb= Stb::find($id);
        $stb->name = $request->name;
        $stb->ram = $request->ram;
        $stb->internal = $request->internal;
        $stb->save();
        return redirect()->route('stb')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Stb!']);
    }



    public function destroy($id)
    {
        try {
            $stb = Stb::findOrFail($id);
            $stb->delete();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Stb Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Stb Tidak Bisa Dihapus Karena Masih digunakan oleh Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
