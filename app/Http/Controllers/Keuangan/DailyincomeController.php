<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DailyincomeController extends Controller
{

    public function index()
    {
        $payment = Payment::whereDate('created_at', today())->get();

        $data = [
            'type_menu' => 'Keuangan',
            'page_name' => 'Pendapatan Harian',
            'income' => $payment->sum('amount')

        ];
        return view('pages.keuangan.income-harian.index', $data);
    }
    public function getData()
    {

        $payment = Payment::all();

        return DataTables::of($payment)->addIndexColumn()->addColumn('action', function ($item) {

            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }




            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
                                                     class="fa-solid fa-bolt"></i></button>';
            }


            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->rawColumns(['action'])->make(true);
    }
}
