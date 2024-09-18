<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SubcriptionController extends Controller
{
    public function getData(Request $request)
    {
        $customerId = $request->input('id');
        $subcription = Subscription::with(['customer', 'paket'])->where('customer_id', $customerId)->orderByDesc('id')->get();
 
        $highlightedData = $subcription->filter(function ($item) {
            return $item->start_date > now();
        })->first();
        $hidedelete = $subcription->filter(function ($item) {
            return $item->start_date < now();
        })->first();
        
        return DataTables::of($subcription)->addIndexColumn()->addColumn('action', function ($item) use ($highlightedData,$hidedelete) {
            
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
            if ($hidedelete && $item->id == $hidedelete->id) {
            if ($userauth->can('delete-customer')) {
                $button .= ' <button class="btn btn-sm btn-danger action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
        }

 if ($highlightedData && $item->id == $highlightedData->id) {
     if ($userauth->can('delete-customer')) {
         $button .= ' <button class="btn btn-sm btn-warning action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('customer.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Re New Pelanggan"><i
                                                     class="fa-solid fa-bolt"></i></button>';
     }
 }
 
 if ($userauth->can('update-customer')) {
    $button .= ' <a href="' . route('print.standart', ['id' => $item->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
}

            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('company', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->paket->name;
        })->editColumn('nominal', function ($data) {
            return 'Rp' . ' ' . number_format($data->paket->price);
        })->rawColumns(['action', 'company', 'paket', 'nominal'])->make(true);
    }



    public function PrintStandart($id){
        $sub = Subscription::where('customer_id',$id)->first();
        $cus = Customer::find($sub->customer->id);
    $data=[
'customer' =>$cus,
'subcription'=> $sub
    ];

    return view('pages.customer.print',$data);

    }
}



