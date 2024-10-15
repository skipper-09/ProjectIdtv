<?php

namespace App\Http\Controllers\Monitor;

use App\Http\Controllers\Controller;
use App\Models\CurentStream;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class monitoringcustomercontroller extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Curent Stream Customer',
        ];
        return view('pages.monitor.index', $data);
    }


    public function getData()
    {
        $curentstream = CurentStream::with(['chanel','customer'])->orderByDesc('id')->get();
        return DataTables::of($curentstream)->addIndexColumn()->addColumn('action', function ($categori) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-categori')) {
                $button .= ' <a href="' . route('categori-chanel.edit', ['id' => $categori->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $categori->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-categori')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $categori->id . ' data-type="delete" data-route="' . route('categori-chanel.delete', ['id' => $categori->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('device_id',function($data){
            return $data->device_id;
        })->editColumn('name',function($data){
            return $data->customer->name;
        })->editColumn('chanel',function($data){
            return $data->chanel->name;
        })->editColumn('created_at',function($data){
            return  Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
        })->editColumn('mac',function($data){
            return $data->customer->mac;
        })->rawColumns(['device_id','name','chanel','created_at'])->make(true);
    }
}
