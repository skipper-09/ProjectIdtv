<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class DailyincomeController extends Controller
{

    public function index()
    {
        $payment = Payment::where('created_at', now())->get();
        $data = [
            'type_menu' => '',
            'page_name' => 'Pendapatan Harian',
            'payment' => $payment,
        ];
        return view('pages.keuangan.income-harian.index', $data);
    }
    public function getData(Request $request)
    {
        $customerId = $request->input('id');
        $subcription = Subscription::with(['customer', 'paket'])->where('customer_id', $customerId)->orderByDesc('id')->get();

        $hidedelete = $subcription->filter(function ($item) {
            return $item->end_date < now();
        })->first();
        $highlightedData = $subcription->filter(function ($item) {
            return $item->end_date > now();
        })->first();

        return DataTables::of($subcription)->addIndexColumn()->addColumn('action', function ($item) use ($highlightedData, $hidedelete) {

            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
            if ($hidedelete && $item->id == $hidedelete->id) {
                if ($userauth->can('delete-customer')) {
                    $button .= ' <button class="btn btn-sm btn-danger action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
                }
            }


            if ($highlightedData && $item->id == $highlightedData->id) {
                if ($userauth->can('delete-customer')) {
                    $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
                                                     class="fa-solid fa-bolt"></i></button>';
                }
            }

            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('company', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->paket->name;
        })->editColumn('nominal', function ($data) {
            return 'Rp' . ' ' . number_format($data->paket->price);
        })->rawColumns(['action', 'company', 'paket', 'nominal'])->make(true);
    }
}
