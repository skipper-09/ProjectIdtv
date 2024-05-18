<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(): View
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Customer'
        ];
        return view('pages.customer.index', $data);
    }
    public function getData()
    {
        $customer = Customer::query();
        return DataTables::of($customer)->addIndexColumn()->addColumn('action', function ($customer) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('customer.edit', ['id' => $customer->id]) . '" class="btn btn-sm btn-success action" data-id=' . $customer->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $customer->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $customer->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return $button;
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Kategori',

        ];
        return view('pages.customer.index', $data);
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        Customer::create($data);
        return redirect()->route('categori-chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Membuat Kategori!']);
    }

    public function show()
    {
       
    }

    public function update()
    {

        // $categori = Categori::find($id);
        // $categori->name = $request->name;
        // $categori->save();
        // return redirect()->route('categori-chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Kategori!']);
    }

    public function destroy($id)
    {
        Customer::where('id', $id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Kategori Berhasil Dihapus!.',
        ]);
    }
}
