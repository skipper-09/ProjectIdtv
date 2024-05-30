<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Stb;
use App\Models\User;
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
        $data = Stb::query();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-owner')) {
                $button .= ' <a href="' . route('stb.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-owner')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('stb.delete', ['id' => $data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }

    public function destroy($id)
    {
      
        $stb = Stb::where('id', $id)->delete();
        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Stb Berhasil Dihapus!.',
        ]);
    }
}
