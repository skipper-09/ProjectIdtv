<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SubcriptionController extends Controller
{
    public function getData(Request $request)
    {
        $customerId = $request->input('id');
        $subcription = Subscription::with(['customer', 'paket'])->where('customer_id', $customerId)->orderByDesc('id')->get();

        $hidedelete = $subcription->filter(function ($item) {
            return $item->end_date < now() || $item->status == 0;
        })->first();
        $highlightedData = $subcription->filter(function ($item) {
            return $item->end_date > now();
        })->first();

        return DataTables::of($subcription)->addIndexColumn()->addColumn('action', function ($item) use ($highlightedData, $hidedelete) {

            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            // if ($userAuth->can(['read-customer'])) {
            //     $button .= ' <button  class="btn btn-sm btn-primary mr-1 action" data-id=' . $customer->id . ' data-type="show" data-route="' . route('customer.detail', ['id' => $customer->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Show Data"><i
            //                                                 class="fas fa-eye"></i></button>';
            // }
            if ($hidedelete && $item->id == $hidedelete->id) {
                if ($userauth->can('delete-customer')) {
                    $button .= ' <button class="btn btn-sm btn-danger action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('keuangan.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
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
                $button .= ' <a href="' . route('print.standart', ['id' => $item->id, 'type' => 'subscription']) . '" class="btn btn-sm btn-success action mr-1" target="_blank" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="PRINT INVOICE"><i
                                                class="fa-solid fa-print"></i></a>';
            }

            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('company', function ($data) {
            return $data->customer->company->name;
        })->editColumn('paket', function ($data) {
            return $data->paket->name;
        })->editColumn('nominal', function ($data) {
            return 'Rp' . ' ' . number_format($data->paket->price);
        })->editColumn('invoices', function ($data) {
            return $data->status == 1 ? $data->invoices : $data->invoices . "<span class='badge badge-danger ml-1'>Unpaid</span>";
        })->rawColumns(['action', 'company', 'paket', 'nominal', 'invoices'])->make(true);
    }



    public function destroy($id)
    {
        try {
            Subscription::where('id', $id)->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Menghapus Data!',
                'trace' => $e->getTrace()
            ]);
        }
    }



    public function PrintStandart($id, $type)
    {



        if ($type === 'subscription') {
            $sub = Subscription::with(['payment'])->find($id);
            $cus = Customer::find($sub->customer_id);
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub
            ];
        } elseif ($type === 'income') {
            $paymen = Payment::find($id);
            $sub = Subscription::find($paymen->subscription_id);
            $cus = Customer::find($sub->customer_id);
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub
            ];
        } else {
            $cus = Customer::find($id);
            $sub = Subscription::where('customer_id', $cus->id)->orderBy('created_at', 'desc')->first();
            $paymen = Payment::where('subscription_id', $sub->id)->first();
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub
            ];
        }
        return view('pages.customer.print', $data);
    }
}
