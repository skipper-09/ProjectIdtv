<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRequest;
use App\Models\Company;
use App\Models\owner;
use App\Models\User;
use Exception;
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
        $owner = owner::query()->get();
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
        })->rawColumns(['action'])->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'company',
            'page_name' => 'Tambah Pemilik',
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
            'username' => $request->username,
            'address' => $request->address,
            'showpassword' => $request->password,
            'password' => $request->password,
        ]);

        return redirect()->route('owner')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Pemilik Prusahaan!']);
    }

    public function show(owner $owner, $id)
    {

        $data = [
            'type_menu' => 'company',
            'page_name' => 'Edit Pemilik',
            'owner' => $owner->find($id),
            'company' => Company::all()
        ];
        return view('pages.company.owner.editowner', $data);
    }

    public function update(OwnerRequest $request, $id)
    {

        $request->validated();
        $owner = owner::findOrFail($id);
        $owner->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'username' => $request->username,
            'address' => $request->address,
            'showpassword' => $request->password,
            'password' => $request->password,
        ]);
        // $owner->name = $request->name;
        // $owner->address = $request->address;
        // $owner->phone = $request->phone;
        // $owner->company_id = $request->company_id;
        // $owner->email = $request->email;
        // $owner->password = $request->password;
        // $owner->save();
        return redirect()->route('owner')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Data Pemilik!']);
    }

    public function destroy($id)
    {
        try {
            $owner = owner::findOrFail($id);
            $owner->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Pemilik Perusahaan Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Pemilik Perusahaan Gagal dihapus!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
