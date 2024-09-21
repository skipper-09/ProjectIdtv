<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class StatistikController extends Controller
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
}
