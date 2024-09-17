<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SubcriptionController extends Controller
{
    public function getData()
    {
        $customer = Subscription::with(['customer', 'paket'])->orderByDesc('id')->get();

        return DataTables::of($customer)->addIndexColumn()->addColumn('action', function ($customer) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
           
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $customer->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('company',function($data){
            return $data->customer->company->name;
        })->editColumn('paket',function($data){
            return $data->paket->name;
        })->editColumn('nominal',function($data){
            return 'Rp'.' '.number_format($data->paket->price);
        })->rawColumns(['action','company','paket','nominal'])->make(true);
    }
}
