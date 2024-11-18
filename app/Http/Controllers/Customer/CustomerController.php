<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Jobs\SendWhatsAppMessage;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Reseller;
use App\Models\ResellerPaket;
use App\Models\Stb;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    // public function index(): View
    // {
    //     $active = 0;
    //     $inactive = 0;
    //     $allcus = 0;
    //     if (auth()->user()->hasRole('Reseller')) {
    //         $reseller = Reseller::where('user_id', '=', auth()->id())->first();
    //         $active = Customer::where('is_active', 1)->where('reseller_id', $reseller->id)->get()->count();
    //         $inactive = Customer::where('is_active', 0)->where('reseller_id', $reseller->id)->get()->count();
    //         $allcus = Customer::where('reseller_id', $reseller->id)->get()->count();
    //     } else {
    //         $active = Customer::where('is_active', 1)->get()->count();
    //         $inactive = Customer::where('is_active', 0)->get()->count();
    //         $allcus = Customer::all()->count();
    //     }
    //     $data = [
    //         'type_menu' => '',
    //         'page_name' => 'Customer',
    //         'company' => Company::all(),
    //         'customer' => $allcus,
    //         'cusactive' => $active,
    //         'cusinactive' => $inactive,
    //     ];
    //     return view('pages.customer.index', $data);
    // }


    // public function getcompany(Request $request)
    // {
    //     $data['company_id'] = Region::where('company_id', $request->company_id)->get();
    //     return response()->json($data);
    // }


    // public function getData(Request $request)
    // {

    //     if (auth()->user()->hasRole('Reseller')) {
    //         $company = Company::where('user_id', '=', auth()->id())->first();
    //         $customer = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller'])->where('company_id', $company->id)->orderByDesc('id')->get();
    //     } else if (auth()->user()->hasRole('CS')) {
    //         $customer = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller'])->where('user_id', auth()->id())->orderByDesc('id')->get();
    //     } else {

    //         if ($request->has('filter') && !empty($request->input('filter'))) {
    //             $customer = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller'])->where('company_id', $request->input('filter'))->orderBy('id', 'desc')->get();

    //             // $customer->where('company_id', $request->input('filter'))->orderBy('id', 'desc')->get();
    //         } else {
    //             $customer = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller'])->orderByDesc('id')->get();
    //         }
    //     }
    //     return DataTables::of($customer)->addIndexColumn()->addColumn('action', function ($customer) {
    //         $userauth = User::with('roles')->where('id', Auth::id())->first();
    //         $button = '';
    //         if ($userauth->can(['reset-device'])) {
    //             $button .= ' <button  class="btn btn-sm btn-warning mr-1 action" data-id=' . $customer->id . ' data-type="reset" data-route="' . route('customer.reset', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Rset Device"><i
    //                                                         class="fas fa-power-off"></i></button>';
    //         }
    //         if ($userauth->can(['read-customer'])) {
    //             $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
    //                                                         class="fas fa-eye"></i></button>';
    //         }
    //         if ($userauth->can('update-customer')) {
    //             $button .= ' <a href="' . route('customer.edit', ['id' => $customer->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $customer->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i
    //                                                         class="fa-solid fa-pencil"></i></a>';
    //         }
    //         if ($userauth->can('delete-customer')) {
    //             $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $customer->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
    //                                                         class="fa-solid fa-trash"></i></button>';
    //         }

    //         return '<div class="d-flex">' . $button . '</div>';
    //     })->addColumn('is_active', function ($chanel) {
    //         $active = '';
    //         $chanel->is_active == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
    //         return $active;
    //     })->editColumn('stb', function (Customer $stb) {
    //         return $stb->stb->name;
    //     })->editColumn('reseller', function (Customer $stb) {
    //         return $stb->reseller->name ?? '';
    //     })->editColumn('company', function ($company) {
    //         return optional($company->company)->name ?? 'Tidak ada perusahaan';
    //     })->editColumn('region', function (Customer $region) {
    //         return $region->region->name;
    //     })->editColumn('start_date', function (Customer $sub) {
    //         return $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date;
    //     })->editColumn('end_date', function (Customer $sub) {
    //         $cus = '';

    //         // Ambil subscription berdasarkan customer_id dan pastikan start_date tidak null
    //         $activeSubscription = $sub->subcrib()
    //             ->where('customer_id', $sub->id)
    //             ->whereNotNull('start_date')
    //             ->orderBy('created_at', 'desc')  // Ambil yang terbaru berdasarkan waktu pembuatan
    //             ->first();

    //         if ($activeSubscription) {
    //             // Cek apakah end_date sudah lebih kecil dari hari ini
    //             if ($activeSubscription->end_date < today()->format('Y-m-d')) {
    //                 // Jika tanggal sudah lewat, tampilkan dengan teks merah
    //                 $cus = '<span class="text-danger">' . $activeSubscription->end_date . '</span>';
    //             } else {
    //                 // Jika tanggal masih valid, tampilkan dengan teks hijau tebal
    //                 $cus = '<span class="text-success fw-bold">' . $activeSubscription->end_date . '</span>';
    //             }
    //         }

    //         return $cus;
    //     })->editColumn('created_at', function (Customer $date) {
    //         return date('d-m-Y', strtotime($date->created_at));
    //     })->addColumn('renew', function ($customer) {
    //         $userauth = User::with('roles')->where('id', Auth::id())->first();
    //         $button = '';

    //         // Retrieve the latest subscription for the customer
    //         $latestSubscription = $customer->subcrib()
    //             ->whereNotNull('start_date') // Ensure the subscription has started
    //             ->orderBy('created_at', 'desc')
    //             ->first();

    //         if ($latestSubscription) {
    //             // Calculate if today is within 3 days of the end_date
    //             $endDate = Carbon::parse($latestSubscription->end_date);
    //             $threeDaysBeforeEnd = $endDate->subDays(3);
    //             $today = Carbon::now();

    //             // Determine if the renew button should be active or disabled
    //             $isPaid = $latestSubscription->status == 0; // Assuming there is an 'is_paid' field

    //             if ($userauth->can(['renew-customer'])) {
    //                 if ($isPaid) {
    //                     // If the subscription is paid, disable the renew button
    //                     $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' disabled title="Subscription already paid"><i class="fa-solid fa-bolt"></i></button>';
    //                 } else {
    //                     if ($today->greaterThanOrEqualTo($threeDaysBeforeEnd)) {
    //                         // Enable the renew button if within 3 days of the end date
    //                         if ($userauth->can('renew-customer')) {
    //                             $button .= ' <a href="' . route('customer.renew', ['id' => $customer->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $customer->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Renew"><i class="fa-solid fa-bolt"></i></a>';
    //                         }
    //                     } else {
    //                         // Otherwise, disable the renew button
    //                         $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' disabled title="Cannot renew until 3 days before end date"><i class="fa-solid fa-bolt"></i></button>';
    //                     }
    //                 }
    //             }
    //         }

    //         // Add print button if the user has permission
    //         if ($userauth->can('read-customer')) {
    //             $button .= ' <a href="' . route('print.standart', ['id' => $customer->id, 'type' => 'customer']) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $customer->id . ' target="_blank" data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="fa-solid fa-print"></i></a>';
    //         }

    //         return '<div class="d-flex">' . $button . '</div>';
    //     })->rawColumns(['action', 'renew', 'is_active', 'stb', 'company', 'region', 'created_at', 'start_date', 'end_date', 'is_active', 'reseller'])->make(true);
    // }

    public function index(): View
    {
        $active = 0;
        $inactive = 0;
        $allcus = 0;
        if (auth()->user()->hasRole('Reseller')) {
            $reseller = Reseller::where('user_id', '=', auth()->id())->first();
            $active = Customer::where('is_active', 1)->where('reseller_id', $reseller->id)->get()->count();
            $inactive = Customer::where('is_active', 0)->where('reseller_id', $reseller->id)->get()->count();
            $allcus = Customer::where('reseller_id', $reseller->id)->get()->count();
        } else {
            $active = Customer::where('is_active', 1)->get()->count();
            $inactive = Customer::where('is_active', 0)->get()->count();
            $allcus = Customer::all()->count();
        }
        $data = [
            'type_menu' => '',
            'page_name' => 'Customer',
            'company' => Company::all(),
            'resellers' => Reseller::all(), // Add this line
            'customer' => $allcus,
            'cusactive' => $active,
            'cusinactive' => $inactive,
        ];
        return view('pages.customer.index', $data);
    }

    public function getData(Request $request)
    {
        if (auth()->user()->hasRole('Reseller')) {
            $reseller = Reseller::where('user_id', '=', auth()->id())->first();
            $customer = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller'])
                ->where('reseller_id', $reseller->id)
                ->orderByDesc('id')
                ->get();
        } else {
            $query = Customer::with(['region', 'stb', 'company', 'subcrib', 'reseller']);

            // Apply company filter if provided
            if ($request->has('filter') && !empty($request->input('filter'))) {
                $query->where('company_id', $request->input('filter'));
            }

            // Apply reseller filter if provided
            if ($request->has('reseller') && !empty($request->input('reseller'))) {
                $query->where('reseller_id', $request->input('reseller'));
            }

            $customer = $query->orderByDesc('id')->get();
        }

        return DataTables::of($customer)
            ->addIndexColumn()
            ->addColumn('action', function ($customer) {
                $userauth = User::with('roles')->where('id', Auth::id())->first();
                $button = '';
                if ($userauth->can(['reset-device'])) {
                    $button .= ' <button  class="btn btn-sm btn-warning mr-1 action" data-id=' . $customer->id . ' data-type="reset" data-route="' . route('customer.reset', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Rset Device"><i
                                                        class="fas fa-power-off"></i></button>';
                }
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
            })
            ->addColumn('is_active', function ($chanel) {
                $active = '';
                $chanel->is_active == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
                return $active;
            })
            ->editColumn('stb', function (Customer $stb) {
                return $stb->stb->name;
            })
            ->editColumn('reseller', function (Customer $stb) {
                return $stb->reseller->name ?? '';
            })
            ->editColumn('company', function ($company) {
                return optional($company->company)->name ?? 'Tidak ada perusahaan';
            })
            ->editColumn('region', function (Customer $region) {
                return $region->region->name;
            })
            ->editColumn('start_date', function (Customer $sub) {
                return $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $sub->subcrib()->where('customer_id', $sub->id)->orderBy('created_at', 'asc')->first()->start_date;
            })
            ->editColumn('end_date', function (Customer $sub) {
                $cus = '';
                $activeSubscription = $sub->subcrib()
                    ->where('customer_id', $sub->id)
                    ->whereNotNull('start_date')
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($activeSubscription) {
                    if ($activeSubscription->end_date < today()->format('Y-m-d')) {
                        $cus = '<span class="text-danger">' . $activeSubscription->end_date . '</span>';
                    } else {
                        $cus = '<span class="text-success fw-bold">' . $activeSubscription->end_date . '</span>';
                    }
                }

                return $cus;
            })
            ->editColumn('created_at', function (Customer $date) {
                return date('d-m-Y', strtotime($date->created_at));
            })
            ->addColumn('renew', function ($customer) {
                $userauth = User::with('roles')->where('id', Auth::id())->first();
                $button = '';

                $latestSubscription = $customer->subcrib()
                    ->whereNotNull('start_date')
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestSubscription) {
                    $endDate = Carbon::parse($latestSubscription->end_date);
                    $threeDaysBeforeEnd = $endDate->subDays(3);
                    $today = Carbon::now();
                    $isPaid = $latestSubscription->status == 0;

                    if ($userauth->can(['renew-customer'])) {
                        if ($isPaid) {
                            $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' disabled title="Subscription already paid"><i class="fa-solid fa-bolt"></i></button>';
                        } else {
                            if ($today->greaterThanOrEqualTo($threeDaysBeforeEnd)) {
                                if ($userauth->can('renew-customer')) {
                                    $button .= ' <a href="' . route('customer.renew', ['id' => $customer->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $customer->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Renew"><i class="fa-solid fa-bolt"></i></a>';
                                }
                            } else {
                                $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' disabled title="Cannot renew until 3 days before end date"><i class="fa-solid fa-bolt"></i></button>';
                            }
                        }
                    }
                }

                if ($userauth->can('read-customer')) {
                    $button .= ' <a href="' . route('print.standart', ['id' => $customer->id, 'type' => 'customer']) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $customer->id . ' target="_blank" data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="fa-solid fa-print"></i></a>';
                }

                return '<div class="d-flex">' . $button . '</div>';
            })
            ->rawColumns(['action', 'renew', 'is_active', 'stb', 'company', 'region', 'created_at', 'start_date', 'end_date', 'is_active', 'reseller'])
            ->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Customer',
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),
            'paket' => Package::where('type_paket', 'main')->get(),
            'reseller' => Reseller::all(),
            'paketreseller' => ResellerPaket::where('status', 1)->get(),
            'getPaketResellerRoute' => route('customer.getPaketReseller')
        ];
        return view('pages.customer.addcustomer', $data);
    }

    public function getPaketReseller(Request $request)
    {
        $resellerId = $request->input('reseller_id');

        if (!$resellerId) {
            return response()->json(['message' => 'Reseller ID is required'], 400);
        }

        $paketReseller = ResellerPaket::where('reseller_id', $resellerId)
            ->where('status', 1)
            ->get();

        return response()->json($paketReseller);
    }


    public function store(Request $request, )
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'mac' => 'required|string', // MAC address format
            'nik' => 'required|string|regex:/^[0-9]+$/', // NIK harus 16 digit angka
            'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/', // Nomor telepon hanya angka dan panjang antara 10-15
            'address' => 'required|string|max:500', // Maksimal 500 karakter
            'region_id' => 'required|integer|exists:regions,id', // Pastikan region_id ada di tabel regions
            'stb_id' => 'required|integer|exists:stbs,id', // Pastikan stb_id ada di tabel stbs
            'username' => 'required|string|unique:customers,username|max:255', // username unik di tabel customers
            'password' => 'required|string|min:6|max:255|confirmed', // Password harus dikonfirmasi (pastikan ada `password_confirmation` di request)
            'password_confirmation' => 'required|string|min:6|max:255',
            'is_active' => 'required|boolean', // Nilai boolean (0 atau 1)
            'end_date' => 'required',

        ]);

        if ($request->type == null) {
            return redirect()->back()->with(['status' => 'Eror!', 'message' => 'Gagal Tipe Customer Wajib Kamu pilih!']);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'mac' => $request->mac,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $request->region_id,
            'stb_id' => $request->stb_id,
            'company_id' => $request->type == 'perusahaan' ? $request->company_id : null,
            'username' => $request->username,
            'showpassword' => $request->password,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
            'reseller_id' => $request->type == 'reseller' ? $request->reseller_id : null,
            'type' => $request->type,
            'resellerpaket_id' => $request->type == 'reseller' ? $request->resellerpaket_id : null,
            'paket_id' => $request->type == 'perusahaan' ? $request->paket_id : null,

        ]);
        $resellerpaket = ResellerPaket::find($request->resellerpaket_id);
        $paketutama = Package::find($request->paket_id);
        $subs = Subscription::create([
            'customer_id' => $customer->id,
            'packet_id' => $request->paket_id == null ? $resellerpaket->paket_id : $request->paket_id,
            'reseller_package_id' => $request->resellerpaket_id == null ? null : $request->resellerpaket_id,
            'start_date' => Carbon::now(),
            'end_date' => $request->end_date,
            'fee' => $request->paket_id == null ? $resellerpaket->price : 0,
            'tagihan' => $request->paket_id == null ? $resellerpaket->total : $paketutama->price
        ]);


        //insert to payment table
        Payment::create([
            'subscription_id' => $subs->id,
            'customer_id' => $customer->id,
            'amount' => $subs->tagihan,
            'fee' => $subs->fee,
            'tanggal_bayar' => now(),
            'status' => 'paid'
        ]);

        // // Jadwalkan pengiriman pesan WhatsApp
        // $wa = new SendWhatsAppMessage(
        //     $request->name,
        //     $request->address,
        //     $request->username,
        //     $request->password,
        //     $request->phone
        // );

        // $this->dispatch($wa);
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
        $latestsub = Subscription::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->first();
        $data = [
            'type_menu' => '',
            'page_name' => 'Update Customer',
            'customer' => $customer,
            'stb' => Stb::all(),
            'region' => Region::all(),
            'company' => Company::all(),
            'paket' => Package::where('type_paket', 'main')->get(),
            'reseller' => Reseller::all(),
            'paketreseller' => ResellerPaket::where('status', 1)->get(),
            'latestsubcribe' => $latestsub,
        ];

        return view('pages.customer.editcustomer', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'mac' => 'required|string', // MAC address format
            'nik' => 'required|string|regex:/^[0-9]+$/', // NIK harus 16 digit angka
            'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/', // Nomor telepon hanya angka dan panjang antara 10-15
            'address' => 'required|string|max:500', // Maksimal 500 karakter
            'region_id' => 'required|integer|exists:regions,id', // Pastikan region_id ada di tabel regions
            'stb_id' => 'required|integer|exists:stbs,id', // Pastikan stb_id ada di tabel stbs
            'username' => 'required|string|max:255|unique:customers,username,' . $id, // username unik di tabel customers
            'password' => 'required|string|min:6|max:255|confirmed', // Password harus dikonfirmasi (pastikan ada `password_confirmation` di request)
            'password_confirmation' => 'required|string|min:6|max:255',
            'is_active' => 'required|boolean', // Nilai boolean (0 atau 1)
            'end_date' => 'required',
        ]);

        if ($request->type == null) {
            return redirect()->back()->with(['status' => 'Eror!', 'message' => 'Gagal Tipe Customer Wajib Kamu pilih!']);
        }
        $customer = Customer::find($id);
        $customer->update([
            'name' => $request->name,
            'mac' => $request->mac,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $request->region_id,
            'stb_id' => $request->stb_id,
            'company_id' => $request->type == 'perusahaan' ? $request->company_id : null,
            'username' => $request->username,
            'showpassword' => $request->password,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
            'reseller_id' => $request->type == 'reseller' ? $request->reseller_id : null,
            'type' => $request->type,
            'resellerpaket_id' => $request->type == 'reseller' ? $request->resellerpaket_id : null,
            'paket_id' => $request->type == 'perusahaan' ? $request->paket_id : null,
        ]);
        $resellerpaket = ResellerPaket::find($request->resellerpaket_id);

        if ($customer->id != null) {
            $subs = Subscription::where('customer_id', $customer->id)->orderBy('id', 'desc')->first();
            $subs->update([
                'packet_id' => $request->paket_id == null ? $resellerpaket->paket_id : $request->paket_id,
                'reseller_package_id' => $request->resellerpaket_id == null ? null : $request->resellerpaket_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'fee' => $request->paket_id == null ? $resellerpaket->price : 0,
                'tagihan' => $request->paket_id == null ? $resellerpaket->total : 0
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

    public function resetDevice($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['device_id' => null]);
        //return response
        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => 'Device Berhasil Direset!.',
        ]);
    }




    public function RenewSubscription($id)
    {
        // Fetch the most recent subscription for the customer
        $subs = Subscription::where('customer_id', $id)->orderBy('id', 'desc')->first();

        // Initialize variables for paket lists
        $resellerPaket = collect();  // Default empty collection for ResellerPaket
        $paket = collect();       // Default empty collection for Package

        // Check if the subscription exists
        if ($subs) {
            if ($subs->customer->type == 'reseller') {
                // If the customer is a reseller, get the ResellerPaket based on reseller_package_id
                $resellerPaket = ResellerPaket::where('id', $subs->reseller_package_id)->first();
            } else {
                // If the customer is not a reseller, get the Package based on packet_id
                $paket = Package::where('id', $subs->packet_id)->first();
            }
        }

        // Prepare data to pass to the view
        $data = [
            'type_menu' => '',
            'page_name' => 'Perpanjang Layanan Customer',
            'resellerPaket' => $resellerPaket,  // ResellerPaket list
            'paket' => $paket,  // This will either contain ResellerPaket or Package based on the condition
            'subs' => $subs,    // Pass the subscription to the view
        ];

        // Return the view with the data
        return view('pages.customer.renew', $data);
    }


    public function RenewSubscriptionAdd($id, Request $request)
    {
        // Coba untuk mencari langganan pelanggan
        try {

            // Mencari langganan terakhir untuk customer_id yang diberikan
            $subs = Subscription::where('customer_id', $id)->orderBy('id', 'desc')->first();

            // Pastikan langganan ditemukan
            if (!$subs) {
                // Jika langganan tidak ditemukan, cetak pesan kesalahan dan redirect
                // \Log::error('Langganan tidak ditemukan untuk customer_id: ' . $id);
                return redirect()->back()->withErrors(['message' => 'Langganan tidak ditemukan.']);
            }

            // Log informasi langganan yang ditemukan
            // \Log::info('Langganan ditemukan: ' . json_encode($subs));

            // Periksa apakah customer adalah reseller
            if ($subs->customer->type == 'reseller') {
                // Jika customer adalah reseller, gunakan reseller_package_id
                $subs->update([
                    'reseller_package_id' => $request->reseller_package_id,  // Paket reseller yang dipilih
                    'status' => 1,
                    'start_date' => now(),
                    'end_date' => $request->end_date,
                ]);
            } else {
                // Jika customer bukan reseller, gunakan packet_id
                $subs->update([
                    'packet_id' => $request->paket_id,  // Paket reguler yang dipilih
                    'status' => 1,
                    'start_date' => now(),
                    'end_date' => $request->end_date,
                ]);
            }

            // Simpan data pembayaran
            $payment = Payment::create([
                'subscription_id' => $subs->id,
                'customer_id' => $subs->customer_id,
                'amount' => $subs->tagihan,
                'fee' => $subs->fee,
                'status' => 1,
                'tanggal_bayar' => now(),
                'payment_type' => 'manual',
            ]);

            // Update status pelanggan menjadi aktif
            Customer::where('id', $subs->customer_id)->update(['is_active' => 1]);


            // //send wa
            // $name = $subs->customer->name;
            // $phone = $subs->customer->phone;
            // $amount = $subs->tagihan;
            // //send to wa
            // $pesan = "Halo, *$name*!\n\nPembayaran Anda telah berhasil.\n\nDetail pembayaran:\nNama: *$name*\nJumlah Pembayaran: *Rp $amount*\nTanggal Pembayaran: *$payment->created_at*\n\nTerima kasih telah melakukan pembayaran. Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.";

            // $params = [
            //     [
            //         'name' => 'phone',
            //         'contents' => $phone
            //     ],
            //     [
            //         'name' => 'message',
            //         'contents' => $pesan
            //     ]
            // ];


            // $auth = env('WABLAS_TOKEN');
            // $url = env('WABLAS_URL');

            // $response = Http::withHeaders([
            //     'Authorization' => $auth,
            // ])->asMultipart()->post("$url/api/send-message", $params);

            // $responseBody = json_decode($response->body());

            // Redirect dengan pesan sukses
            return redirect()->route('customer')
                ->with([
                    'status' => 'Success!',
                    'message' => 'Berhasil Perpanjang Layanan Customer!',
                    'new_tab_url' => route('print.standart', ['id' => $subs->id, 'type' => 'subscription'])
                ]);
        } catch (Exception $e) {
            // Tangkap kesalahan dan catat ke log
            // \Log::error('Gagal memperpanjang langganan: ' . $e->getMessage());

            // Redirect dengan pesan kesalahan
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan saat memperpanjang layanan.']);
        }
    }



    public function CustomerRegister(Request $request)
    {
        try {
            $kode = $request->input('kode');
            $reseller = Reseller::where('referal_code', $kode)->first();

            if ($reseller) {
                $resellerpaket = ResellerPaket::where('reseller_id', $reseller->id)->get();
            }
            $paket = Package::where('type_paket', 'main')->get();

            $data = [
                'page_name' => 'Customer',
                'paket' => isset($resellerpaket) && $resellerpaket->isNotEmpty() ? $resellerpaket : $paket,
                'reseller' => $reseller,
            ];
            return view('pages.customer.publicregister', $data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function customerpost(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'nik' => 'required|string|regex:/^[0-9]+$/', // NIK harus 16 digit angka
                'email' => 'required|string|email', // NIK harus 16 digit angka
                'phone' => 'required', // Nomor telepon hanya angka dan panjang antara 10-15
                'paket_id' => 'required',
                'address' => 'required|string|max:500', // Maksimal 500 karakter
                'username' => 'required|string|unique:customers,username|max:255', // username unik di tabel customers
                'password' => 'required|string|min:6|max:255|confirmed', // Password harus dikonfirmasi (pastikan ada `password_confirmation` di request)
                'password_confirmation' => 'required|string|min:6|max:255',
            ],
            [
                'name.required' => 'Nama Wajib Di Isi',
                'email.required' => 'Email Wajib Di Isi',
                'nik.required' => 'Nik Wajib Di Isi',
                'phone.required' => 'No Telepon Wajib Di Isi',
                'paket_id.required' => 'Paket Wajib Di Pilih',
                'address.required' => 'Alamat Wajib Di isi',
                'username.required' => 'Username Wajib Di isi',
            ]
        );

        $kode = $request->input('kode');
        $reseller = Reseller::where('referal_code', $kode)->first();
        $paket = Package::find($request->paket_id);
        $company = Company::find($paket->company_id);
        if ($reseller == null) {
            $customer = Customer::create([
                'name' => $request->name,
                'mac' => rand(10000, 99999),
                'nik' => $request->nik,
                'phone' => $request->phone,
                'address' => $request->address,
                'region_id' => 1,
                'stb_id' => 1,
                'company_id' => $paket->company_id,
                'username' => $request->username,
                'showpassword' => $request->password,
                'password' => Hash::make($request->password),
                'is_active' => 0,
                'paket_id' => $request->paket_id,
            ]);

            Subscription::create([
                'customer_id' => $customer->id,
                'packet_id' => $request->paket_id,
                'reseller_package_id' => $customer->resellerpaket_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth($paket->duration),
                'fee' => 0,
                'status' => false,
                'tagihan' => $paket->price,
            ]);
        } else {
            $customer = Customer::create([
                'name' => $request->name,
                'mac' => rand(10000, 99999),
                'nik' => $request->nik,
                'phone' => $request->phone,
                'address' => $request->address,
                'region_id' => 1,
                'stb_id' => 1,
                'reseller_id' => $reseller->id,
                'username' => $request->username,
                'showpassword' => $request->password,
                'password' => Hash::make($request->password),
                'is_active' => 0,
                'resellerpaket_id' => $request->paket_id,
                'type' => 'reseller',
            ]);
            Subscription::create([
                'customer_id' => $customer->id,
                'packet_id' => $customer->resellerpaket->paket_id,
                'reseller_package_id' => $customer->resellerpaket_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth($customer->resellerpaket->paket->duration),
                'fee' => $customer->resellerpaket->price,
                'status' => false,
                'tagihan' => $customer->resellerpaket->total,
            ]);
        }


        // Subscription::find($subs->id)->update(['tagihan' => $amount]);


        return redirect()->back()->with('success', 'Pendaftaran Berhasil Download Aplikasi Dan Bayar di aplikasi');
    }
}
