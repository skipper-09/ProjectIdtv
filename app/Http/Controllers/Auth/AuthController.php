<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function index()
    {
        $data = [
            'page_name' => 'Login',
        ];

        return view('auth.login', $data);
    }

    public function signin(Request $request)
    {


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            if ($request->remember_me == 'Y') {
                $time = 60 * 60 * 24 * 365;
                Cookie::queue('email', $request->email, $time);
                Cookie::queue('password', $request->password, $time);
            }

            return response()->json([
                'message' => 'Login berhasil'
            ]);
        } else {
            return response()->json([
                'errors' => ['password' => ['Wrong Password']]
            ], 422);
        }
    }

    public function signout()
    {
        Auth::logout();

        return redirect()->route('auth');
    }
}
