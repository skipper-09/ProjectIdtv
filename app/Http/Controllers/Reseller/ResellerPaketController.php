<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Reseller;
use App\Models\ResellerPaket;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ResellerPaketController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Reseller Paket',
        ];
        return view('pages.resellermanagement.resellerpaket.index', $data);
    }

    public function getData()
    {
        $data = ResellerPaket::with(['reseller', 'paket'])->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-resellerpaket')) {
                $button .= ' <a href="' . route('resellerdata-paket.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-resellerpaket')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('resellerdata-paket.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->addColumn('owner', function ($data) {
            return $data->reseller->name;
        })->addColumn('paketutama', function ($data) {
            return $data->paket->name . ' ' . number_format($data->paket->price);
        })->addColumn('durationpaket', function ($data) {
            return $data->paket->duration . ' Bulan';
        })->addColumn('price', function ($data) {
            return number_format($data->price);
        })->addColumn('total', function ($data) {
            return number_format($data->price + $data->paket->price);
        })->addColumn('status', function ($data) {
            $active = '';
            $data->status == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
            return $active;
        })->rawColumns(['action', 'status', 'durationpaket'])->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Tambah Paket Reseller',
            'reseller' => Reseller::all(),
            'paket' => Package::all()
        ];
        return view('pages.resellermanagement.resellerpaket.add', $data);
    }


    public function store(Request $request)
    {

        $request->validate([
            'paket_id' => 'required',
            'reseller_id' => 'required',
            'name' => 'required',
            'price' => 'required||numeric',
            'status' => 'required'
        ]);

        ResellerPaket::create([
            'paket_id' => $request->paket_id,
            'reseller_id' => $request->reseller_id,
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('resellerdata-paket')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Paket Reseller!']);
    }

    public function show($id)
    {
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Edit Paket Reseller',
            'resellerpaket' => ResellerPaket::find($id),
            'reseller' => Reseller::all(),
            'paket' => Package::all()
        ];
        return view('pages.resellermanagement.resellerpaket.edit', $data);
    }



    public function update(Request $request,$id)
    {
$reseller = ResellerPaket::find($id);
        $request->validate([
            'paket_id' => 'required',
            'reseller_id' => 'required',
            'name' => 'required',
            'price' => 'required||numeric',
            'status' => 'required'
        ]);

        $reseller->update([
            'paket_id' => $request->paket_id,
            'reseller_id' => $request->reseller_id,
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('resellerdata-paket')->with(['status' => 'Success!', 'message' => 'Berhasil Update Paket Reseller!']);
    }


    public function destroy($id)
    {
        try {
            $reseller = ResellerPaket::findOrFail($id);
            $reseller->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Reseller Paket Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Menghapus Data Reseller Paket!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
