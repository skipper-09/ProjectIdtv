<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function FinishPayment(Request $request){
        $invoiceid = $request->query('order_id');
        $subs = Subscription::where('invoices',$invoiceid);
        
        // Logika setelah pembayaran berhasil
       
        // $order = Payment::where('subcription_id', $status)->first();
        // $order->status = 'paid';
        // $order->save();


        $paket = Package::find($subs->packet_id);


        $amount = $paket->price + $subs->customer->company->fee_reseller;

        //insert to payment table
        Payment::create([
            'subscription_id' => $subs->id,
            'customer_id' => $subs->customer->id,
            'amount' => $amount,
            'fee'=> $subs->customer->company->fee_reseller,
            'tanggal_bayar' => now(),
            'status' => 'paid',
        'payment_type'=> 'manual',
        ]);

        return view('pages.payment.success');
    }
}
