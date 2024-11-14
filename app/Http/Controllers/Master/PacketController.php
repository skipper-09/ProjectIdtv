<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaketRequest;
use App\Models\Company;
use App\Models\Package;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PacketController extends Controller
{
   
    public function index()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Paket',
        ];
        return view('pages.paket.index', $data);
    }



    public function getData()
    {
        $data = Package::with('company')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-paket')) {
                $button .= ' <a href="' . route('paket.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-paket')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('paket.delete', ['id' => $data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('duration', function ($data) {
            return $data->duration . ' Bulan';
        })->editColumn('price', function ($data) {
            return 'Rp'. ' '.number_format($data->price);
        })->editColumn('company', function ($data) {
            return $data->company->name;
        })->editColumn('type', function ($data) {
            $type = '';
            $data->type_paket == 'reseller' ? $type = '<span class="badge badge-primary">Reseller</span>' : $type = '<span class="badge badge-success">Utama</span>';
            return $type;
        })->editColumn('status', function ($data) {
            $active = '';
            $data->status == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
            return $active;
        })->rawColumns(['action', 'duration','company','status','type'])->make(true);
    }

  
    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Paket',
            'company'=>Company::all()
        ];
        return view('pages.paket.add', $data);
    }

   
    public function store(PaketRequest $request)
    {
        $request->validated();
        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'company_id' => $request->company_id,
            'status' => $request->status,
            'type_paket' => $request->type,
        ]);
        return redirect()->route('paket')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Paket!']);
    }

        public function show($id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Paket',
            'paket' => Package::findOrFail($id),
            'company'=>Company::all()
        ];
        return view('pages.paket.edit', $data);
    }

    
    public function update(PaketRequest $request, $id)
    {
        $request->validated();
        $paket = Package::findOrFail($id);
        $paket->name = $request->name;
        $paket->price = $request->price;
        $paket->duration = $request->duration;
        $paket->company_id = $request->company_id;
        $paket->type_paket = $request->type;
        $paket->status = $request->status;
        $paket->save();
        return redirect()->route('paket')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Paket!']);
    }

  
    public function destroy($id)
    {
        try {
            $paket = Package::findOrFail($id);
            $paket->delete();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Paket Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Paket Tidak Bisa Dihapus Karena Masih digunakan oleh Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
