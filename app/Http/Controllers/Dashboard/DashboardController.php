<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
  public function index()
  {
    $data = [
      'chanel' => Chanel::all(),
      'company' => Company::all(),
      'customer' => Customer::all(),
      'income' => Payment::all(),
      // 'log' => Activity::all(),
      'type_menu' => 'dashboard',
      'page_name' => 'Dashboard',
    ];
    return view('pages.dashboard.index', $data);
  }


  public function getChartData(Request $request)
  {
    // Default untuk rentang waktu 1 minggu
    $currentDate = Carbon::now();



    if ($request->input('range') == 'week') {
      // Ambil tanggal awal dari minggu ini (dari hari Senin)
      $startDate = $currentDate->copy()->startOfWeek();
      $endDate = $currentDate; // Hingga tanggal hari ini
    } else if ($request->input('range') == 'month') {
      // Ambil rentang waktu satu bulan ini
      $startDate = $currentDate->copy()->startOfMonth();
      $endDate = $currentDate; // Hingga tanggal hari ini
    }

    // Ambil data dari database sesuai dengan rentang waktu ini
    $customers = Customer::whereBetween('created_at', [$startDate, $endDate])
      ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
      ->groupBy('date')
      ->get()
      ->keyBy('date'); // Menggunakan keyBy untuk membuat array dengan key sebagai tanggal

    // Siapkan data untuk chart
    $labels = [];
    $data = [];

    // Loop untuk setiap tanggal dalam minggu atau bulan ini
    $currentDateLoop = $startDate->copy(); // Menggunakan copy() agar tidak mengubah $startDate asli
    while ($currentDateLoop->lte($endDate)) {
      $dateStr = $currentDateLoop->format('Y-m-d');

      // Tambahkan label untuk setiap hari
      $labels[] = $currentDateLoop->format('d-m-Y'); // Contoh format: Senin, Selasa, dll

      // Jika ada data untuk tanggal ini, ambil datanya, jika tidak, tambahkan 0
      $data[] = $customers->get($dateStr)->total ?? 0;

      // Lanjutkan ke hari berikutnya
      $currentDateLoop->addDay();
    }

    return response()->json([
      'labels' => $labels,
      'data' => $data
    ]);
  }
}
