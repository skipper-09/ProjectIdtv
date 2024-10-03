<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Chanel;
use App\Models\Subscription;
use Illuminate\Http\Request;

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


    public function HistoryLangganan(Request $request){

        $customerId = $request->input('customer_id');
        if ($customerId) {
            $subs = Subscription::where('customer_id',$customerId)->get();
            if ($subs) {
                return ResponseFormatter::success($subs, 'History Subscription berhasil diambil');
            } else {
                return ResponseFormatter::error($subs, 'History Subscription kosong', 400);
            }
        }
        return ResponseFormatter::error('data kosong', 'Inputkan Customer_id', 400);
        
    }
}
