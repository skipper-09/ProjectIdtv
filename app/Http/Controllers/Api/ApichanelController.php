<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use App\Models\Customer;
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
        $sub = Subscription::with(['customer', 'paket'])->where('invoices', $invoiceid)->where('status', 0)->first();

        if ($sub == null) {
            return response()->json([
                "message" => 'TIDAK ADA INVOICE TERSEBUT'
            ], 404);
        }

        $paket = Package::find($sub->packet_id);
        $random = Str::uuid();  // UUID for unique order_id
        $sub->update(['midtras_random' => $random]);

        $params = [
            'transaction_details' => [
                "order_id" => $random,
                "gross_amount" => $sub->tagihan,
            ],
            'item_details' => [
                [
                    "name" => $paket->name,
                    "price" => $sub->tagihan,
                    "quantity" => 1,
                    "merchant_name" => "IDTV",
                ],
            ],
            'customer_details' => [
                "first_name" => $sub->customer->name,
                "phone" => $sub->customer->phone,
                "billing_address" => [
                    "first_name" => $sub->customer->name,
                    "phone" => $sub->customer->phone,
                    "address" => $sub->customer->address,
                    "country_code" => "IDN",
                ],
            ],
            'enabled_payments' => [
                "bca_va",
                "bri_va",
                "bni_va",
                "gopay"
            ],
            // Set expiry to 5 minutes (5 * 60 seconds)
            'expiry' => [
                "start_time" => date("Y-m-d H:i:s T"),  // Current time in ISO 8601 format with timezone
                "unit" => "minute",
                "duration" => 5  // Set to 5 minutes
            ],
        ];

        // Authorization using Base64 encoding of Server Key
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $responseBody = json_decode($response->body());

        if ($response->failed()) {
            Log::error('Midtrans Error: ' . $response->body());
            return response()->json(['message' => 'Terjadi kesalahan pada transaksi'], 500);
        }

        $sub->update(['midtras_link' => $responseBody->redirect_url]);

        return ResponseFormatter::success($responseBody, 'Success');
    }





    public function handleNotification(Request $request)
    {
        // Ambil order_id dari request
        $orderId = $request->input('order_id');
        $statuscode = $request->input('status_code');
        $gross_amount = $request->input('gross_amount');
        $calculated_signature_key = $request->input('signature_key');
        $auth = env('MIDTRANS_SERVER_KEY');
        // SHA512(order_id + status_code + gross_amount + serverkey);
        $data = $orderId . $statuscode . $gross_amount . $auth;
        $signature_key = hash('sha512', $data);

        if ($calculated_signature_key !== $signature_key) {
            // Jika signature key tidak valid, tolak notifikasi
            return response()->json(['message' => 'Invalid signature'], 403);
        }



        // Get the transaction status from the response
        $transactionStatus = $request->input('transaction_status') ?? null;

        // Cari order berdasarkan ID
        $subs = Subscription::where('midtras_random', $orderId)->first();

        if ($subs) {
            $paket = Package::find($subs->packet_id);
            $amount = $subs->tagihan;

            switch ($transactionStatus) {
                case 'capture':
                case 'settlement':
                    $subs->update([
                        'status' => 1,
                        'start_date' => now(),
                        'end_date' => now()->addMonth($paket->duration)->toDateString(),
                    ]);
                    Customer::where('id', $subs->customer_id)->update(['is_active' => 1]);

                    // Insert to payment table
                    $paymnet = Payment::create([
                        'subscription_id' => $subs->id,
                        'customer_id' => $subs->customer->id,
                        'amount' => $amount,
                        'fee' => $subs->customer->company->fee_reseller,
                        'tanggal_bayar' => now(),
                        'status' => 'paid',
                        'payment_type' => 'midtrans',
                    ]);

                    $name = $subs->customer->name;
                    $phone = $subs->customer->phone;
                    //send to wa
                    $pesan = "Halo, *$name*!\n\nPembayaran Anda telah berhasil.\n\nDetail pembayaran:\nNama: *$name*\nJumlah Pembayaran: *Rp $amount*\nTanggal Pembayaran: *$paymnet->created_at*\n\nTerima kasih telah melakukan pembayaran. Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.";



                    $params = [
                        [
                            'name' => 'phone',
                            'contents' => $phone
                        ],
                        [
                            'name' => 'message',
                            'contents' => $pesan
                        ]
                    ];


                    $auth = env('WABLAS_TOKEN');
                    $url = env('WABLAS_URL');

                    $response = Http::withHeaders([
                        'Authorization' => $auth,
                    ])->asMultipart()->post("$url/api/send-message", $params);

                    $responseBody = json_decode($response->body());

                    break;

                case 'pending':
                case 'deny':
                case 'cancel':
                case 'expire':
                    $subs->update(['status' => 0, 'midtras_link' => null]);
                    break;
            }
        } else {
            Log::warning('Subscription not found for invoice:', ['invoice' => $orderId]);
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
