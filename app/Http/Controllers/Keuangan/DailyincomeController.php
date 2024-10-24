<?php

namespace App\Http\Controllers\Keuangan;

use App\Exports\DailyIncomeExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\DataTables;

class DailyincomeController extends Controller
{

    public function index()
    {
        $payment = Payment::whereDate('created_at', today())->get();

        $data = [
            'type_menu' => 'Keuangan',
            'page_name' => 'Pendapatan Harian',
            'income' => $payment->sum('amount'),
            'incomeclean' => $payment->sum('amount') - $payment->sum('fee'),
            'reseller' => $payment->sum('fee')

        ];
        return view('pages.keuangan.income-harian.index', $data);
    }
    public function getData()
    {

        $payment = Payment::with(['customer', 'subscrib',])->whereDate('created_at', now())->orderByDesc('created_at')->get();

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
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id, 'type' => 'income']) . '" class="btn btn-sm btn-success action mr-1" target="_blank" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('nik', function ($data) {
            return $data->customer->nik;
        })->editColumn('invoice', function ($data) {
            return $data->subscrib->invoices;
        })->editColumn('customer', function ($data) {
            return $data->customer->name;
        })->editColumn('owner', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->subscrib->paket->name;
        })->editColumn('pokok', function ($data) {
            return number_format($data->subscrib->paket->price);
        })->editColumn('payment_type', function ($data) {
            return $data->payment_type;
        })->editColumn('fee', function ($data) {
            return number_format($data->customer->company->fee_reseller);
        })->editColumn('start_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->end_date;
        })->editColumn('created_at', function ($data) {
            return
                Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at, 'UTC')
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
        })->rawColumns(['action', 'customer', 'status', 'paket', 'nik', 'start_date', 'owner', 'pokok', 'fee', 'payment_type', 'invoice'])->make(true);
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
}
