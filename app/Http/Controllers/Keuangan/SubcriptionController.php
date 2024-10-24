<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SubcriptionController extends Controller
{
    public function getData(Request $request)
    {
        $customerId = $request->input('id');
        $subcription = Subscription::with(['customer', 'paket'])->where('customer_id', $customerId)->orderByDesc('id')->get();

        $hidedelete = $subcription->filter(function ($item) {
            return $item->end_date < now();
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
            if ($hidedelete && $item->id == $hidedelete->id || !$highlightedData == $item->id) {
                if ($userauth->can('delete-customer')) {
                    $button .= ' <button class="btn btn-sm btn-danger action mr-1" data-id=' . $item->id . ' data-type="delete" data-route="' . route('keuangan.delete', ['id' => $item->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete Data"><i
                                                            class="fa-solid fa-trash"></i></button>';
                }
            }


            if ($highlightedData && $item->id == $highlightedData->id) {
                $latestSubscription = Subscription::find($item->id)
                ->whereNotNull('start_date') // Ensure the subscription has started
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestSubscription) {
                // Calculate if today is within 3 days of the end_date
                $endDate = Carbon::parse($latestSubscription->end_date);
                $threeDaysBeforeEnd = $endDate->subDays(3);
                $today = Carbon::now();

                // Determine if the renew button should be active or disabled
                $isPaid = $latestSubscription->status == 0; // Assuming there is an 'is_paid' field

                if($userauth->can(['renew-customer'])){
                    if ($isPaid) {
                        // If the subscription is paid, disable the renew button
                        $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $item->id . ' disabled title="Subscription already paid"><i class="fa-solid fa-bolt"></i></button>';
                    } else {
                        if ($today->greaterThanOrEqualTo($threeDaysBeforeEnd)) {
                            // Enable the renew button if within 3 days of the end date
                            if ($userauth->can('update-customer')) {
                                $button .= ' <a href="' . route('customer.renew', ['id' => $item->customer_id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $item->id . ' data-type="edit" data-toggle="tooltip" data-placement="bottom" title="Renew"><i class="fa-solid fa-bolt"></i></a>';
                            }
                        } else {
                            // Otherwise, disable the renew button
                            $button .= ' <button class="btn btn-sm btn-primary mr-1 action" data-id=' . $item->id . ' disabled title="Cannot renew until 3 days before end date"><i class="fa-solid fa-bolt"></i></button>';
                        }
                    }
                }
            }}

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
                'subcription' => $sub,
                'type'=> 'subscription',
                'id'=>$id
            ];
        } elseif ($type === 'income') {
            $paymen = Payment::find($id);
            $sub = Subscription::find($paymen->subscription_id);
            $cus = Customer::find($sub->customer_id);
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub,
                'type'=> 'income',
                'id'=>$id
            ];
        } else {
            $cus = Customer::find($id);
            $sub = Subscription::where('customer_id', $cus->id)->orderBy('created_at', 'desc')->first();
            $paymen = Payment::where('subscription_id', $sub->id)->first();
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub,
                'type' =>'customer',
                'id'=>$id
            ];
        }
        return view('pages.customer.print', $data);
    }



    public function PrintThermal($id, $type)
    {
        if ($type === 'subscription') {
            $sub = Subscription::with(['payment'])->find($id);
            $cus = Customer::find($sub->customer_id);
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub,
                'payment'=> $sub->payment[0]->created_at,
            ];
        } elseif ($type === 'income') {
            $paymen = Payment::find($id);
            $sub = Subscription::find($paymen->subscription_id);
            $cus = Customer::find($sub->customer_id);
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub,
                'payment'=> $paymen->created_at,
            ];
        } else {
            $cus = Customer::find($id);
            $sub = Subscription::where('customer_id', $cus->id)->orderBy('created_at', 'desc')->first();
            $paymen = Payment::where('subscription_id', $sub->id)->first();
            $data = [
                'page_name' => $sub->invoices,
                'customer' => $cus,
                'subcription' => $sub,
                'payment'=> $paymen->created_at
            ];
        }
        return view('pages.customer.printthermal', $data);
    }
}
