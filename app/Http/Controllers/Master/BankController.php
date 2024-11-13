<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BankController extends Controller
{
    public function index()
    {
        $data = [
            
            'type_menu' => 'master',
            'page_name' => 'Bank',

        ];
        return view('pages.master.bank.index', $data);
    }

    public function getData()
    {
        $data = Bank::all();
        return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-bank')) {
                $button .= ' <a href="' . route('bank.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-bank')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('bank.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => 'master',
            'page_name' => 'Tambah Bank',

        ];
        return view('pages.master.bank.add', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required'
        ],['name.required'=>'Nama Bank wajib di isi.']);
        Bank::create($data);
        return redirect()->route('bank')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Bank!']);
    }


    public function show(Bank $bank, $id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Bank',
            'bank' => $bank->find($id)
        ];
        return view('pages.master.bank.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required'
        ],['name.required'=>'Nama Bank wajib di isi.']);
        $bank = Bank::find($id);
        $bank->name = $request->name;
        $bank->save();
        return redirect()->route('bank')->with(['status' => 'Success!', 'message' => 'Berhasil Update Bank!']);
    }


    public function destroy($id)
    {
        try {
            $bank = Bank::findOrFail($id);
            $bank->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Bank Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data Bank gagal dihapus',
                'trace' => $e->getTrace()
            ]);
        }
    }

}
