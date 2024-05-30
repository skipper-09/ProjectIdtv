<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use App\Models\Company;
use App\Models\Customer;
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
      // 'log' => Activity::all(),
      'type_menu' => 'dashboard',
      'page_name' => 'Dashboard',
    ];
    return view('pages.dashboard.index', $data);
  }
}
