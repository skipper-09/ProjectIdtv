<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Reseller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ResellerController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Reseller',
        ];
        return view('pages.resellermanagement.reseller.index', $data);
    }



    public function getData()
    {
        $data = Reseller::with('bank', 'user', 'company')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-reseller')) {
                $button .= ' <a href="' . route('resellerdata.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-reseller')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('resellerdata.delete', ['id' => $data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('bank', function ($data) {
            return $data->bank->name;
        })->editColumn('user', function ($data) {
            return $data->user->name;
        })->editColumn('company', function ($data) {
            return $data->company->name;
        })->rawColumns(['action',])->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Tambah Reseller',
            'bank' => Bank::all(),
            'company' => Company::all(),
            'owner' => User::with('roles')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Reseller');
                })->whereNotIn('name', ['Developer'])
                ->whereDoesntHave('reseller')
                ->get(),
        ];
        return view('pages.resellermanagement.reseller.add', $data);
    }


    public function store(Request $request)
    {
        
            $request->validate([
                'company_id' => 'required',
                'user_id' => 'required',
                'bank_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'rekening' => 'required',
                'owner_rek' => 'required'
            ],[
                'company_id.required'=>'Perusahaan Wajib dipilih',
                'user_id.required'=>'Pemilik Reseller Wajib dipilih',
                'bank_id.required'=>'Bank Wajib dipilih',
                'name.required'=>'Nama Wajib diisi',
                'address.required'=>'Alamat Reseller Wajib dipilih',
                'rekening.required'=>'Rekening Wajib di isi',
                'owner_rek.required'=>'Nama Pemilik Rekening Wajib di isi',
                'phone.required'=>'No Telepon Wajib di isi',
            ]);

            Reseller::create([
                'company_id'=>$request->company_id,
                'user_id'=>$request->user_id,
                'bank_id'=>$request->bank_id,
                'name'=>$request->name,
                'address'=>$request->address,
                'phone'=>$request->phone,
                'rekening'=>$request->rekening,
                'owner_rek'=>$request->owner_rek,
            ]);
            return redirect()->route('resellerdata')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Reseller!']);   
    }



    public function update(Request $request,$id)
    {
        
            $request->validate([
                'company_id' => 'required',
                'user_id' => 'required',
                'bank_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'rekening' => 'required',
                'owner_rek' => 'required'
            ],[
                'company_id.required'=>'Perusahaan Wajib dipilih',
                'user_id.required'=>'Pemilik Reseller Wajib dipilih',
                'bank_id.required'=>'Bank Wajib dipilih',
                'name.required'=>'Nama Wajib diisi',
                'address.required'=>'Alamat Reseller Wajib dipilih',
                'rekening.required'=>'Rekening Wajib di isi',
                'owner_rek.required'=>'Nama Pemilik Rekening Wajib di isi',
                'phone.required'=>'No Telepon Wajib di isi',
            ]);

            $reseller = Reseller::find($id);
            $reseller->update([
                'company_id'=>$request->company_id,
                'user_id'=>$request->user_id,
                'bank_id'=>$request->bank_id,
                'name'=>$request->name,
                'address'=>$request->address,
                'phone'=>$request->phone,
                'rekening'=>$request->rekening,
                'owner_rek'=>$request->owner_rek,
            ]);
            return redirect()->route('resellerdata')->with(['status' => 'Success!', 'message' => 'Berhasil Update Reseller!']);   
    }



    public function show($id)
    {
    
        $data = [
            'type_menu' => 'reseller',
            'page_name' => 'Edit Reseller',
            'bank' => Bank::all(),
            'company' => Company::all(),
            'owner' => User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Reseller');
            })->whereNotIn('name', ['Developer'])
            ->get(),
            'reseller'=>Reseller::find($id),
        ];
        return view('pages.resellermanagement.reseller.edit', $data);
    }





    public function destroy($id)
    {
        try {
            $reseller = Reseller::findOrFail($id);
            $reseller->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Reseller Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Reseller gagal dihapus',
                'trace' => $e->getTrace()
            ]);
        }
    }

}