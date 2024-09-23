<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class LogController extends Controller
{
    public function index()
    {
        $data = [
            'user' => User::all(),
            'type_menu' => 'setting',
            'page_name' => 'Log Activity',
        ];
        return view('pages.settings.log.index', $data);
    }
    public function getData()
    {
        $activity = Activity::with('causer')->orderBy('created_at', 'desc')->get();
        return DataTables::of($activity)->addIndexColumn()->editColumn('causer',function($data){
            return $data->causer == null ? '-' : $data->causer->name;
        })->editColumn('created_at',function($data){
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
        })->editColumn('description',function($data){
            $properties = json_decode($data->properties, true);
            $old = isset($properties['old']) ? json_encode($properties['old']) : 'N/A';
            $new = isset($properties['attributes']) ? json_encode($properties['attributes']) : 'N/A';
            return "{$data->description}<br>Old Values: {$old}<br>New Values: {$new}";
        })->rawColumns(['causer','created_at','description'])->make(true);
    }
}
