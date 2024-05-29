<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRequest;
use App\Models\Company;
use App\Models\owner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OwnerController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Pemilik',
        ];
        return view('pages.company.owner.index', $data);
    }


    public function getData()
    {
        $owner = owner::query()->with(['company'])->get();
        return DataTables::of($owner)->addIndexColumn()->addColumn('action', function ($owner) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-owner')) {
                $button .= ' <a href="' . route('owner.edit', ['id' => $owner->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $owner->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-owner')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $owner->id . ' data-type="delete" data-route="' . route('owner.delete', ['id' => $owner->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('company', function (owner $owner) {
            return $owner->company->name;
        })->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Pemilik',
            'company' => Company::all()
        ];
        return view('pages.company.owner.addowner', $data);
    }

    public function store(OwnerRequest $request)
    {
        $request->validated();
        owner::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'company_id' => $request->company_id,
            'password' => $request->password,
        ]);

        return redirect()->route('owner')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Pemilik Prusahaan!']);
    }

    public function show(){
        
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Pemilik',
        ];
        return view('pages.company.owner.editowner', $data);
        
    }

    public function update(){

    }

    public function destroy($id)
    {
        owner::where('id', $id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Pemilik Perusahaan Berhasil Dihapus!.',
        ]);
    }
}
