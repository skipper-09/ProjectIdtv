<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Stb;
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
            'page_name' => 'Customer',
        ];
        return view('pages.customer.index', $data);
    }
    public function getData()
    {
        $customer = Customer::query()->with(['region', 'stb', 'company'])->get();

        return DataTables::of($customer)->addIndexColumn()->addColumn('action', function ($customer) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can(['read-customer'])) {
                $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
                                                            class="fas fa-eye"></i></button>';
            }
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('customer.edit', ['id' => $customer->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $customer->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $customer->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->addColumn('is_active', function ($chanel) {
            $active = '';
            $chanel->is_active == true ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
            return $active;
        })->editColumn('stb', function (Customer $stb) {
            return $stb->stb->name;
        })->editColumn('company', function (Customer $company) {
            return $company->region->company->name;
        })->editColumn('region', function (Customer $region) {
            return $region->region->name;
        })->editColumn('created_at', function (Customer $date) {
            return date('d-m-Y', strtotime($date->created_at));
        })->rawColumns(['action', 'is_active', 'stb', 'company', 'region', 'created_at'])->make(true);
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

    public function detail($id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Customer',
            'customer' => Customer::where('id', $id)->get()

        ];

        return view('pages.customer.detail-customer', $data);
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
