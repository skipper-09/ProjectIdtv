<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaketRequest;
use App\Models\Company;
use App\Models\Package;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PacketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $data = Package::all();
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
        })->editColumn('company_id', function ($data) {
            return $data->company->name;
        })->rawColumns(['action', 'duration'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Paket',
            'company' => Company::all()
        ];
        return view('pages.paket.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaketRequest $request)
    {
        $data = $request->validated();
        Package::create($data);
        return redirect()->route('paket')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Paket!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Paket',
            'paket' => Package::findOrFail($id)
        ];
        return view('pages.paket.edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaketRequest $request, $id)
    {
        $request->validated();
        $paket = Package::findOrFail($id);
        $paket->name = $request->name;
        $paket->price = $request->price;
        $paket->duration = $request->duration;
        $paket->save();
        return redirect()->route('paket')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Paket!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $stb = Package::where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Paket Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Stb Tidak Bisa Dihapus Karena Masih digunakan oleh Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
