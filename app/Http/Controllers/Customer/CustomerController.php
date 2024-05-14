<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $data = [
            'customer' => Customer::all(),
            'type_menu' => 'dashboard',
            'page_name' => 'Customer'
        ];
        return view('pages.customer.index', $data);
    }
}
