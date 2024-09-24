<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
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
            $old = isset($properties['old']) ? implode(',',$properties['old']) : 'N/A';
            $new = isset($properties['attributes']) ? implode(', ', $properties['attributes']) : 'N/A';
            $result = '';
            if ($data->event == 'update') {
                $result = "{$data->description} <strong>{$old}</strong> to <strong>{$new}</strong>";
            }elseif($data->event =='created'){
                $result = "{$data->description} <strong>{$new}</strong>";
            }else{
                $result = "{$data->description} <strong>{$old}</strong>";
            }
            return $result;
        })->rawColumns(['causer','created_at','description'])->make(true);
    }


    public function cleanlog(){
        try{
            Activity::truncate();
        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => 'User Berhasil Dihapus!.',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ]);
    }
    }
}
