<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Stb;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'company'=>Company::all()
        ];
        return view('pages.customer.index', $data);
    }


    // public function getcompany(Request $request)
    // {
    //     $data['company_id'] = Region::where('company_id', $request->company_id)->get();
    //     return response()->json($data);
    // }


    public function getData(Request $request)
    {
        
        if (auth()->user()->hasRole('Reseller')) {
            $company = Company::where('user_id', '=', auth()->id())->first();
            $customer =Customer::with(['region', 'stb', 'company', 'subcrib'])->where('company_id', $company->id)->orderBy('id', 'asc')->get();
        } else { 
            
                if ($request->has('filter') && !empty($request->input('filter'))) {
                    $customer = Customer::with(['region', 'stb', 'company', 'subcrib'])->where('company_id', $request->input('filter'))->orderBy('id', 'asc')->get();

                    // $customer->where('company_id', $request->input('filter'))->orderBy('id', 'desc')->get();
                }else{
                    $customer = Customer::with(['region', 'stb', 'company', 'subcrib'])->orderBy('id', 'asc')->get();
                }
        }
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
        })->editColumn('company', function ($company) {
            return optional($company->company)->name ?? 'Tidak ada perusahaan';
        })->editColumn('region', function (Customer $region) {
            return $region->region->name;
        })->editColumn('start_date', function (Customer $sub) {
            return $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function (Customer $sub) {
            $cus = '';
            if ($sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->end_date < today()->format('Y-m-d')) {
                $cus = '<span class="text-danger">' . $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->end_date . '</span>';
            } else {
                $cus = '<span class="text-success fw-bold">' . $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->end_date . '</span>';
            }
            return $cus;
        })->editColumn('created_at', function (Customer $date) {
            return date('d-m-Y', strtotime($date->created_at));
        })->addColumn('renew', function ($customer) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userauth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('customer.edit', ['id' => $customer->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $customer->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="ReNew"><i
                                                            class="fa-solid fa-bolt"></i></a>';
            }
            if ($userauth->can('read-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $customer->id, 'type' => 'customer']) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $customer->id . ' target="_blank" data-type="edit" data-toggle="tooltip" data-placement="bottom" title="ReNew"><i
                class="fa-solid fa-print"></i></a>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->rawColumns(['action', 'renew', 'is_active', 'stb', 'company', 'region', 'created_at', 'start_date', 'end_date','is_active'])->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Customer',
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),
            'paket' => Package::all()

        ];
        return view('pages.customer.addcustomer', $data);
    }

    public function store(CustomerRequest $request,)
    {
        // dd($request);
        $request->validated();
        $customer = Customer::create([
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
            'is_active' => $request->is_active,
        ]);
        $company = Company::find($request->company_id);
        
        $subs = Subscription::create([
            'customer_id' => $customer->id,
            'packet_id' => $request->paket_id,
            'start_date' => Carbon::now(),
            'end_date' => $request->end_date,
            'fee' => $company->fee_reseller ?? 0,
        ]);

        $paket = Package::find($subs->packet_id);


        $amount = $paket->price + $customer->company->fee_reseller;

        //insert to payment table
        Payment::create([
            'subcription_id' => $subs->id,
            'customer_id' => $customer->id,
            'amount' => $amount,
            'fee'=> $customer->company->fee_reseller,
            'tanggal_bayar' => now(),
            'status' => 'paid'
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

        $customer = Customer::find($id);
        $latestsub = Subscription::where('customer_id', $customer->id)->orderBy('created_at', 'asc')->first();
        $data = [
            'type_menu' => '',
            'page_name' => 'Update Customer',
            'customer' => $customer,
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),
            'paket' => Package::all(),
            'latestsubcribe' => $latestsub
        ];

        return view('pages.customer.editcustomer', $data);
    }

    public function update(Request $request, $id)
    {
        // $request->validated();
        $customer = Customer::find($id);
        $customer->update([
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
            'is_active' => $request->is_active,
        ]);

        if ($customer->id != null) {

            $subs = Subscription::where('customer_id', $customer->id)->first();
            $subs->update([
                'packet_id' => $request->paket_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
        }





        // $paket = Package::where('id', $subs->packet_id)->get();
        // $paymentid = Payment::where('customer_id', $customer->id)->get();
        // $payment = Payment::find($paymentid);
        // $payment->update([
        //     'subcription_id' => $subs->id,
        //     'customer_id' => $customer->id,
        //     'amount' => $paket[0]->price,
        //     'status' => 'paid'
        // ]);
        return redirect()->back()->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Customer!']);
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
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


    // public function getPaket($company_id){
    //     $packages = Package::where('company_id', $company_id)->get();

    // return response()->json($packages);
    // }
}
