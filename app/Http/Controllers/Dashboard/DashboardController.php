<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
  public function index()
  {
    $lastactivity = Activity::with('causer')->orderBy('created_at', 'desc')->take(4)->get();
    // Decode JSON properties
    foreach ($lastactivity as $activity) {
      $activity->properties = json_decode($activity->properties, true);
    }

    if (!auth()->user()->hasRole('Reseller')) {
      $data = [
        'chanel' => Chanel::all(),
        'company' => Company::all(),
        'customer' => Customer::whereMonth('created_at',now())->get(),
        'income' => Payment::all(),
        'log' =>  $lastactivity,
        'type_menu' => 'dashboard',
        'page_name' => 'Dashboard',
      ];
      return view('pages.dashboard.index', $data);
    }


    $currentMonthStart = \Carbon\Carbon::now()->startOfMonth(); // Tanggal 1 bulan ini
    $currentMonthEnd = \Carbon\Carbon::now()->endOfMonth();


    $company = Company::where('user_id', auth()->id())->first();
    $data = [
      'customer' => Customer::where('company_id', '=', $company->id)->whereMonth('created_at',\Carbon\Carbon::now()->month)->get(),
      'income' => Payment::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->whereHas('customer', function ($query) use ($company) {
        $query->where('company_id', $company->id);
      }),
      'type_menu' => 'dashboard',
      'page_name' => 'Dashboard',
    ];
    return view('pages.dashboard.reseller', $data);
  }


  public function getChartData(Request $request)
  {
    // Default untuk rentang waktu 1 minggu
    $currentDate = \Carbon\Carbon::now();



    if ($request->input('range') == 'week') {
      // Ambil tanggal awal dari minggu ini (dari hari Senin)
      $startDate = $currentDate->copy()->startOfWeek();
      $endDate = $currentDate; // Hingga tanggal hari ini
    } else if ($request->input('range') == 'month') {
      // Ambil rentang waktu satu bulan ini
      $startDate = $currentDate->copy()->startOfMonth();
      $endDate = $currentDate; // Hingga tanggal hari ini
    }

    $user = auth()->user();
    $company = Company::where('user_id', '=', auth()->id())->first();



    // Ambil data dari database sesuai dengan rentang waktu ini
    if ($user->hasRole('Reseller')) {
      $customers = Customer::whereBetween('created_at', [$startDate, $endDate])->where('company_id', '=', $company->id)
        ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->groupBy('date')
        ->get()
        ->keyBy('date'); // Menggunakan keyBy untuk membuat array dengan key sebagai tanggal
    } else {
      $customers = Customer::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->groupBy('date')
        ->get()
        ->keyBy('date'); // Menggunakan keyBy untuk membuat array dengan key sebagai tanggal
    }

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
