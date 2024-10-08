<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function FinishPayment($order_id,Request $request){       

        $subs = Subscription::where('invoices',$order_id)->first();
        
       


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

        return view('pages.payment.success');
    }

}
