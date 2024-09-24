<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\owner;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Perusahaan',
        ];
        return view('pages.company.company.index', $data);
    }


    public function getData()
    {
        $company = Company::with('owner')->get();
        return DataTables::of($company)->addIndexColumn()->addColumn('action', function ($company) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-company')) {
                $button .= ' <a href="' . route('company.edit', ['id' => $company->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $company->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-company')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $company->id . ' data-type="delete" data-route="' . route('company.delete', ['id' => $company->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('owner_id', function ($company) {
            return $company->owner->name;
        })->rawColumns(['action', 'owner_id'])->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Tambah Perusahaan',
            'owner' => owner::doesntHave('company')->get()
        ];
        return view('pages.company.company.addcompany', $data);
    }
    public function store(CompanyRequest $request)
    {
        $request->validated();
        Company::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'owner_id' => $request->owner_id
        ]);

        return redirect()->route('company')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Prusahaan!']);
    }


    public function show(owner $owner, $id)
    {
        $company = Company::find($id);
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Edit Perusahaan',
            'company' => $company,
            'owner' => $owner->all()
        ];
        return view('pages.company.company.editcomapny', $data);
    }


    public function update(CompanyRequest $request, $id)
    {
        $request->validated();
        $owner = Company::findOrFail($id);
        $owner->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'owner_id' => $request->owner_id
        ]);
        return redirect()->route('company')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Data Perusahaan!']);
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Perusahaan Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Perusahaan Tidak Bisa Dihapus Karena Masih digunakan oleh Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
