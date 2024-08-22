<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Customer::where('username', $request->username)->first();
        $token = $user->createToken('api_token')->plainTextToken;
        if (
            Auth::guard('customer')->attempt(['username' => $user->username, 'password' => $request->password])
        ) {
            // Auth::loginUsingId($user->id);
            $data = Customer::where('id', Auth::guard('customer')->user()->id)->with('subcrib')->get();
            return response()->json([
                'message' => 'succesfully logged in Customer Account',
                'data' => Auth::guard('customer')->user(),
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'message' => 'Login failed Usernama dan Password Salah',

            ]);
        }
    }
}
