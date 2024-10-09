<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ApichanelController extends Controller
{
    public function index(Request $request)
    {

        $id = $request->input('id');
        $name = $request->input('name');
        $stream = $request->input('url');

        if ($id) {
            $chanel = Chanel::with('categori')->find($id);
            if ($chanel) {
                return ResponseFormatter::success($chanel, 'Chanel berhasil diambil');
            } else {
                return ResponseFormatter::error($chanel, 'Data Chanel tidak ada', 400);
            }
        }

        $chanel = Chanel::with('categori');
        if ($name) {
            $chanel->where('name', 'like', '%' . $name . '%');
        }
        if ($stream) {
            $chanel->where('replacement_url', 'like', '%' . $name . '%');
        }
        return ResponseFormatter::success($chanel->get(), 'Chanel berhasil diambil');
    }

    public function category(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

        if ($id) {
            $category = Categori::with('chanel')->find($id);
            if ($category) {
                return ResponseFormatter::success($category, 'Category retrieved successfully');
            } else {
                return ResponseFormatter::error($category, 'Category not found', 404);
            }
        }

        $categories = Categori::with('chanel');
        if ($name) {
            $categories->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($categories->get(), 'Categories retrieved successfully');
    }


    public function HistoryLangganan(Request $request)
    {

        $customerId = $request->input('customer_id');
        if ($customerId) {
            $subs = Subscription::with(['payment'])->where('customer_id', $customerId)->get();
            if ($subs) {
                return ResponseFormatter::success($subs, 'History Subscription berhasil diambil');
            } else {
                return ResponseFormatter::error($subs, 'History Subscription kosong', 400);
            }
        }
        return ResponseFormatter::error('data kosong', 'Inputkan Customer_id', 400);
    }


    public function createPayment(Request $request)
    {
        $invoiceid = $request->query('order_id');
        $sub = Subscription::with(['customer', 'paket'])->where('invoices', $invoiceid)->where('status',0)->first();
        if ($sub == null) {
            return response()->json([
                "message" => 'TIDAK ADA INVOICE TERSEBUT'
            ]);
        }
        $paket = Package::find($sub->packet_id);


        $gross_amount = $paket->price + $sub->customer->company->fee_reseller;
        $random = str::uuid();
        $sub->update(['midtras_random' => $random]);
        $params = array(
            'transaction_details' => [
                "order_id" => $random,
                "gross_amount" => $gross_amount,  // Ensure this matches the sum of item details prices
            ],
            'item_details' => array(
                array(
                    "name" => $paket->name,  // Make sure this value exists
                    "price" => $gross_amount,  // This should be the price per item
                    "quantity" => 1,  // Adjust quantity as needed
                ),
            ),
            'customer_details' => [  // These details should be complete and correct
                "first_name" => $sub->customer->name,  // Ensure $sub->customer->name exists
                "last_name" => $sub->customer->address,  // If Midtrans expects last_name, pass an empty string if not applicable
                "phone" => $sub->customer->phone,  // Ensure this field is not empty
                "notes" => "Thank you for your purchase. Please follow the instructions to pay."
            ],
            'enabled_payments' => [
                "bca_va",
                "bri_va",
                "bni_va",
                "shopeepay"
            ],
            "usage_limit" => 1,
            "page_expiry" => [
                "duration" => 1,
                "unit" => "day"
            ],
            'callbacks' => [
                'finish' => route('finishpayment', ['order_id' => $invoiceid])
            ],
        );

        $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ])->post('https://api.sandbox.midtrans.com/v1/payment-links', $params);
        $response = json_decode($response->body());
        return ResponseFormatter::success($response, 'success');
    }



    public function handleNotification(Request $request)
    {

        // Dapatkan data notifikasi
        // $payload = $request->getContent();
        // $notification = json_decode($payload, true);

        // Log notifikasi (untuk debugging)
        // Log::info('Midtrans Notification:', $notification);

        // Ambil order_id dari request
        $orderId = $request->input('order_id');

        // Dapatkan invoice id
        $lastDashPos = strrpos($orderId, '-');

        // Potong string sampai sebelum '-' terakhir
        $newOrderId = ($lastDashPos !== false) ? substr($orderId, 0, $lastDashPos) : $orderId;

        $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

        // API call to get the transaction status
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ])->get("https://api.sandbox.midtrans.com/v2/$orderId/status");

        // Decode the response
        $responseData = json_decode($response->body(), true);

        // Log the response for debugging
        // Log::info('Midtrans API Response:', $responseData);

        // Get the transaction status from the response
        $transactionStatus = $responseData['transaction_status'] ?? null;

        // Cari order berdasarkan ID
        $subs = Subscription::where('midtras_random', $newOrderId)->first();

        if ($subs) {
            $paket = Package::find($subs->packet_id);
            $amount = $paket->price + $subs->customer->company->fee_reseller;

            switch ($transactionStatus) {
                case 'capture':
                case 'settlement':
                    $subs->update([
                        'status' => 1,
                        'start_date' => now(),
                        'end_date' => now()->addMonth($paket->duration)->toDateString(),
                    ]);

                    // Insert to payment table
                    Payment::create([
                        'subscription_id' => $subs->id,
                        'customer_id' => $subs->customer->id,
                        'amount' => $amount,
                        'fee' => $subs->customer->company->fee_reseller,
                        'tanggal_bayar' => now(),
                        'status' => 'paid',
                        'payment_type' => 'midtrans',
                    ]);
                    break;

                case 'pending':
                case 'deny':
                case 'cancel':
                case 'expire':
                    $subs->update(['status' => 0]);
                    break;
            }
        } else {
            Log::warning('Subscription not found for invoice:', ['invoice' => $newOrderId]);
        }

        return response()->json(['status' => 'success']);
    }


    //     public function handleNotification(Request $request)
//     {




    // //         // Dapatkan data notifikasi
// //         $payload = $request->getContent();
// //         $notification = json_decode($payload, true);

    // //         // // // Log notifikasi (untuk debugging)
// //         // Log::info('Midtrans Notification:', $notification);
// //         $transactionStatus = $notification['transaction_status'];
// //         $orderId = $notification['order_id'];

    // //         // gett the invoice id
//         $lastDashPos = strrpos($request->input('order_id'), '-');

    // // Potong string sampai sebelum '-' terakhir
// if ($lastDashPos !== false) {
//     $neworder_id = substr($request->input('order_id'), 0, $lastDashPos);
// }


    //         $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

    //         $response = Http::withHeaders([
//             'Content-Type' => 'application/json',
//             'Authorization' => "Basic $auth"
//         ])->get("https://api.sandbox.midtrans.com/v2/{{$request->input('order_id')}}/status");
//         dd($response);
//         $response = json_decode($response->body());



    //         // // Cari order berdasarkan ID
//         $subs = Subscription::where('invoices',$neworder_id)->first();

    //         // Logika setelah pembayaran berhasil
//         // $order = Payment::where('subcription_id', $status)->first();
//         // $order->status = 'paid';
//         // $order->save();


    //         $paket = Package::find($subs->packet_id);


    //         $amount = $paket->price + $subs->customer->company->fee_reseller;




    //         if ($subs) {
//             switch ($request->input('transaction_status')) {
//                 case 'capture':
//                 case 'settlement':
//                     $subs->update([
//                         'status'=>1,
//                         'start_date'=>now(),
//                         'end_date'=>now()->addMonth($paket->duration)->toDateString(),
//                                 ]);
//                                 //insert to payment table
//                                 Payment::create([
//                                     'subscription_id' => $subs->id,
//                                     'customer_id' => $subs->customer->id,
//                                     'amount' => $amount,
//                                     'fee'=> $subs->customer->company->fee_reseller,
//                                     'tanggal_bayar' => now(),
//                                     'status' => 'paid',
//                                     'payment_type'=> 'midtrans',
//                                 ]);
//                     break;

    //                 case 'pending':
//                     $subs->update([
//                         'status'=>0,

    //                                 ]);
//                     break;

    //                 case 'deny':
//                 case 'cancel':
//                     $subs->update([
//                         'status'=>0,

    //                                 ]);
//                     break;

    //                 case 'expire':
//                     $subs->update([
//                         'status'=>0,
//                                 ]);
//                     break;
//             }


    //         }

    //          return response()->json(['status' => 'success']);
//     }
}
