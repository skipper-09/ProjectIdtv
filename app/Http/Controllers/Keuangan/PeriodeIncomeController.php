<?php

namespace App\Http\Controllers\Keuangan;

use App\Exports\PeriodeIncomeExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reseller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class PeriodeIncomeController extends Controller
{
    public function index(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'type.required' => 'Tipe Customer harus diisi.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai.',
        ]);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $company_id = $request->input('company_id');
        $type = $request->input('type');
        $reseller_id = $request->input('reseller_id');
        $company = Company::find($company_id);
        $reseller = Reseller::find($reseller_id);

        if ($type == '') {
            return redirect()->back()->with(['status' => 'Error!', 'message' => 'Filter Harus dengan memilih Type']);
        }


        if ($type == 'reseller') {
            if ($reseller_id != null) {
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id) {
                    $query->where('reseller_id', $reseller_id)->whereNull('company_id');
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            } else {
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id, $company_id) {
                    $query->where('company_id', $company_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            }
        } else if ($type == 'perusahaan') {
            if ($company_id != null) {
                // If company_id is not null, filter by company_id
                $payment = Payment::whereHas('customer', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            } else {
                // If company_id is null, show data for all companies
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id) {
                    $query->where('reseller_id', $reseller_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            }
        }

        $perusahaan = '';
        if ($company == null && $reseller == null) {
            $perusahaan = 'Semua Perusahaan';
        } else if ($company != null && $reseller == null) {
            $perusahaan = $company->name;
        } else if ($company == null && $reseller != null) {
            $perusahaan = $reseller->name;
        }
        $data = [
            'type_menu' => 'Keuangan',
            'page_name' => 'Pendapatan Periode',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'company_id' => $company_id,
            'type' => $type,
            'reseller_id' => $reseller_id,
            'company' => $perusahaan,
            'income' => $payment->sum('amount')

        ];
        return view('pages.keuangan.income-periode.index', $data);
    }
    public function getData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $company_id = $request->input('company_id');
        $reseller_id = $request->input('reseller_id');
        $type = $request->input('type');


        if ($type == 'reseller') {
            if ($reseller_id != null) {
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id) {
                    $query->where('reseller_id', $reseller_id)->whereNull('company_id');
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            } else {
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id, $company_id) {
                    $query->where('company_id', $company_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            }
        } else if ($type == 'perusahaan') {
            if ($company_id != null) {
                // If company_id is not null, filter by company_id
                $payment = Payment::whereHas('customer', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            } else {
                // If company_id is null, show data for all companies
                $payment = Payment::whereHas('customer', function ($query) use ($reseller_id) {
                    $query->where('reseller_id', $reseller_id);
                })->whereBetween('created_at', [$startDate, $endDate])->get();
            }
        }

        return DataTables::of($payment)->addIndexColumn()->addColumn('action', function ($item) {

            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
            // if ($userauth->can('delete-customer')) {
            //     $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
            //                                          class="fa-solid fa-bolt"></i></button>';
            // }
            $button .= ' <a href="' . route('print.standart', ['id' => $item->id, 'type' => 'income']) . '" class="btn btn-sm btn-success action mr-1" target="_blank" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';


            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('nik', function ($data) {
            return $data->customer->nik;
        })->editColumn('customer', function ($data) {
            return $data->customer->name;
        })->editColumn('paket', function ($data) {
            return $data->customer->type == 'perusahaan' ? $data->subscrib->paket->name : $data->subscrib->resellerpaket->name;
        })->editColumn('pokok', function ($data) {
            return number_format($data->subscrib->paket->price);
        })->editColumn('fee_reseller', function ($data) {
            return $data->customer->type == 'perusahaan' ? 0 : number_format($data->customer->resellerpaket->price);
        })->editColumn('owner', function ($data) {
            return $data->customer->type == 'perusahaan' ? $data->customer->company->name : $data->customer->reseller->name;
        })->editColumn('start_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->end_date;
        })->editColumn('created_at', function ($data) {
            return
                Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        })->editColumn('status', function ($data) {
            $span = '';
            if ($data->status == 'paid') {
                $span = '<span class="badge badge-success">Lunas</span>';
            } else if ($data->status == 'unpaid') {
                $span = '<span class="badge badge-danger">Belum Bayar</span>';
            } else {
                $span = '<span class="badge badge-warning">Pending</span>';
            }
            return $span;
        })->rawColumns(['action', 'customer', 'status', 'paket', 'nik', 'start_date', 'owner', 'pokok', 'fee_reseller'])->make(true);
    }




    public function PrintStandart($id)
    {

        $paymen = Payment::find($id);
        $sub = Subscription::find($paymen->subcription_id);
        $cus = Customer::find($sub->customer_id);
        $data = [
            'page_name' => $sub->invoices,
            'customer' => $cus,
            'subcription' => $sub
        ];
        return view('pages.customer.print', $data);
    }




    public function ExportData($start, $end, $type)
    {

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        // Menyaring data berdasarkan tanggal dan tipe
        return Excel::download(
            new PeriodeIncomeExport($startDate, $endDate, $type),
            "income_tanggal_{$startDate}_{$endDate}.csv"
        );
    }
}
