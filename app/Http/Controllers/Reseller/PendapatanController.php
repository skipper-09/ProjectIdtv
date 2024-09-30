<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PendapatanController extends Controller
{
    public function index()
    {
        $company = Company::where('user_id', auth()->id())->first();
        $canclaim = Subscription::whereHas('customer',function($query) use ($company){
            $query->where('company_id', $company->id);
        })->sum('fee');
        
        $data = [
            'type_menu' => 'layout',
            'page_name' => 'Pendapatan',
            'claim'=> $canclaim,
        ];
        return view('pages.reseller.pendapatan.index', $data);
    }


    public function getData(){
        $company = Company::where('user_id', auth()->id())->first();
        $payment = Payment::with(['customer', 'subscrib'])->whereHas('subscrib',function($query){
$query->where('status',true);
        })->whereHas('customer',function($query) use($company){
            $query->where('company_id',$company->id);
        })->get();
        return DataTables::of($payment)->addIndexColumn()->addColumn('action', function ($item) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
                                                     class="fa-solid fa-bolt"></i></button>';
            }
            if ($userauth->can('update-customer')) {
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id,'type'=>'income']) . '" class="btn btn-sm btn-success action mr-1" target="_blank" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('nik', function ($data) {
            return $data->customer->nik;
        })->editColumn('customer', function ($data) {
            return $data->customer->name;
        })->editColumn('owner', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->subscrib->paket->name . ' - '. 'Rp. '. number_format($data->subscrib->paket->price);
        })->editColumn('fee', function ($data) {
            return 'Rp. '. number_format($data->subscrib->fee);
        })->editColumn('claim', function ($data) {
            $span = '';
            if ($data->subscrib->is_claim == true) {
                $span = '<span class="badge badge-primary">Sudah Di Claim</span>';
            } else{
                $span = '<span class="badge badge-success">Belum Di Claim</span>';
            }
            return $span;
            
        })->editColumn('start_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function ($data) {
            return
                $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->subscrib->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->end_date;
        })->editColumn('created_at', function ($data) {
            return
                Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at, 'UTC')
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        })->editColumn('status', function ($data) {
            $span = '';
            if ($data->status == 'paid') {
                $span = '<span class="badge badge-success">Lunas</span>';
            } else if ($data->status == 'unpaid') {
                $span = '<span class="badge badge-danger">Belum Bayar</span>';
            } else {
                $span = '<span class="badge badge-warning">Pending</span>';
            }
            return $span;
        })->rawColumns(['action', 'customer', 'status', 'paket', 'nik', 'start_date','owner','fee','claim'])->make(true);
    }
}
