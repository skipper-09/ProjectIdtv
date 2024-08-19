<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Region;
use App\Models\Stb;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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


    public function getcompany(Request $request)
    {
        $data['company_id'] = Region::where('company_id', $request->company_id)->get();
        return response()->json($data);
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
            $chanel->is_active == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
            return $active;
        })->editColumn('stb', function (Customer $stb) {
            return $stb->stb->name;
        })->editColumn('company', function (Customer $company) {
            return $company->company->name;
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
            'page_name' => 'Tambah Customer',
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),

        ];
        return view('pages.customer.addcustomer', $data);
    }

    public function store(CustomerRequest $request)
    {
        $request->validated();
        Customer::create([
            'name' => $request->name,
            'mac' => $request->mac,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $request->region_id,
            'stb_id' => $request->stb_id,
            'company_id' => $request->company_id,
            'username' => $request->username,
            'showpassword' => $request->password,
            'password' => Hash::make($request->password),
            'is_avtive' => $request->is_active,
        ]);



        return redirect()->route('customer')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Customer!']);
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


    public function show($id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Customer',
            'customer' => Customer::find($id),
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),
        ];

        return view('pages.customer.editcustomer', $data);
    }

    public function update(CustomerRequest $request, $id)
    {
        $request->validated();
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->stb_id = $request->stb_id;
        $customer->region_id = $request->region_id;
        $customer->company_id = $request->company_id;
        $customer->mac = $request->mac;
        $customer->nik = $request->nik;
        $customer->showpassword = $request->password;
        if ($request->password === null) {
            $customer->password;
        } else {
            $customer->password = Hash::make($request->password);
        }
        $customer->is_active = $request->is_active == true ? 1 : 0;
        $customer->save();

        return redirect()->route('customer')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Customer!']);
    }

    public function destroy($id)
    {
        try {
            Customer::where('id', $id)->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Customer Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Menghapus Data Customer!',
                'trace' => $e->getTrace()
            ]);
        }
    }
}
