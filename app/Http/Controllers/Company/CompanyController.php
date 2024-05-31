<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
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
        $company = Company::query();
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
        })->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Tambah Perusahaan',
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
        ]);

        return redirect()->route('company')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Prusahaan!']);
    }


    public function show(Company $company, $id)
    {

        $data = [
            'type_menu' => 'company',
            'page_name' => 'Edit Perusahaan',
            'company' => $company->find($id)
        ];
        return view('pages.company.company.editcomapny', $data);
    }


    public function update(CompanyRequest $request, $id)
    {
        $owner = Company::findOrFail($id);
        $owner->update($request->validated());
        return redirect()->route('company')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Data Perusahaan!']);
    }

    public function destroy($id)
    {
        try {
            Company::where('id', $id)->delete();
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
