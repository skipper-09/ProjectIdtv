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
        $sub = Subscription::with(['customer', 'paket'])->where('invoices', $invoiceid)->first();
        if ($sub == null) {
            return response()->json([
                "message" => 'TIDAK ADA INVOICE TERSEBUT'
            ]);
        }
        $paket = Package::find($sub->packet_id);


        $gross_amount = $paket->price + $sub->customer->company->fee_reseller;

        $params = array(
            'transaction_details' => array(
                "order_id" => $invoiceid,
                "gross_amount" => $gross_amount,
            ),
            'customer_required' => false,
            'item_details' => array(
                array(
                    "name" => $paket->name,
                    "price" => $gross_amount,
                    "quantity" => 1,
                ),
            ),

            'customer_details' => array(
                "first_name" => $sub->customer->name . '-' . $sub->customer->address,
                "phone" => $sub->customer->phone,
                "notes" => "Thank you for your purchase. Please follow the instructions to pay."
            ),
            'enabled_payments' => array(
                "credit_card",
                "bca_va",
                "bri_va",
                "bni_va",
                "indomaret",
                "shopeepay"
            ),
            'callbacks' => [
            'finish' => route('finishpayment',['order_id'=>$invoiceid]), // This is the URL to redirect to
    ],
        );

        $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ])->post('https://api.sandbox.midtrans.com/v1/payment-links', $params);
        $response = json_decode($response->body());
        return ResponseFormatter::success($response, 'success');
    }
}
