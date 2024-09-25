<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $paymeent = Payment::selectRaw('SUM(amount) as total, MONTH(created_at) as month')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Mengatur bulan dan total pendapatan ke dalam array
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = 0; // Inisialisasi dengan 0
        }

        foreach ($paymeent as $revenue) {
            $data[$revenue->month] = $revenue->total;
        }

        $data = [
            'type_menu' => 'Keuangan',
            'page_name' => 'Statistik Tahun',
            'data' => $data

        ];
        return view('pages.keuangan.statistik.index', $data);
    }
}
