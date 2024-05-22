<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chanel;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
  public function index()
  {
    $data = [
      'chanel' => Chanel::all(),
      'log' => Activity::all(),
      'type_menu' => 'dashboard',
      'page_name' => 'Dashboard',
    ];
    return view('pages.dashboard.index', $data);
  }
}
