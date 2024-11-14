<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TagihanController extends Controller
{
    public function index()
    {
        // $subs = Subscription::whereDoesntHave('payment')->get();

        $data = [
            'type_menu' => 'Keuangan',
            'page_name' => 'Tagihan Pelanggan',

        ];
        return view('pages.keuangan.tagihan.index', $data);
    }

    public function getData()
    {

        $payment = Subscription::whereDoesntHave('payment')->with(['customer','paket','resellerpaket'])->get();
        

        return DataTables::of($payment)->addIndexColumn()->addColumn('action', function ($item) {

            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
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
            
        })->editColumn('paket', function ($data) {
            return $data->customer->type == 'reseller' ? $data->resellerpaket->name : $data->paket->name;
            
        })->editColumn('owner', function ($data) {
            return $data->customer->type == 'reseller' ? $data->customer->reseller->name : $data->customer->company->name;
            
        })->editColumn('pokok', function ($data) {
            return number_format($data->tagihan);
        })->editColumn('fee', function ($data) {
            return $data->customer->type == 'perusahaan' ? 0 : number_format($data->resellerpaket->price);
        })->editColumn('start_date', function ($data) {
            return
                $data->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date;
        })->editColumn('end_date', function ($data) {
            return
                $data->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->start_date == null ? 'Tidak Ada' : $data->where('customer_id', $data->customer_id)->orderBy('created_at', 'asc')->first()->end_date;
        })->editColumn('created_at', function ($data) {
            return
                Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        })->editColumn('status', function ($data) {
            $span = '';
           
                $span = '<span class="badge badge-danger">Belum Bayar</span>';
           
            return $span;
        })->rawColumns([ 'customer', 'status', 'paket', 'nik', 'start_date','owner','pokok','fee'])->make(true);
    }
}
