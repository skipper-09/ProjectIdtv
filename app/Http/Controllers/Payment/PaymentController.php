<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function FinishPayment(Request $request)
    {

        $order_id = $request->query('order_id');
        if (empty($order_id)) {
            return view('pages.error-404');
        }

        $sub = Subscription::with(['payment'])->where('midtras_random', $order_id)->first();
        $cus = Customer::find($sub->customer_id);
        $data = [
            'page_name' => $sub->invoices,
            'customer' => $cus,
            'subcription' => $sub
        ];
        return view('pages.payment.success', $data);


        //         $paket = Package::find($subs->packet_id);


        //         $amount = $paket->price + $subs->customer->company->fee_reseller;

        //         $subs->update([
// 'status'=>1,
// 'start_date'=>now(),
// 'end_date'=>now()->addMonth($paket->duration)->toDateString(),
//         ]);
//         //insert to payment table
//         Payment::create([
//             'subscription_id' => $subs->id,
//             'customer_id' => $subs->customer->id,
//             'amount' => $amount,
//             'fee'=> $subs->customer->company->fee_reseller,
//             'tanggal_bayar' => now(),
//             'status' => 'paid',
//             'payment_type'=> 'midtrans',
//         ]);


    }

}
