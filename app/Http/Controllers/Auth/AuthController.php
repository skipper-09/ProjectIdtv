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
        $login = $request->email;
        $user = User::where('email', $login)->orWhere('username', $login)->first();


        // if (!$user) {
        //     return redirect()->back()->withErrors(['email' => 'Invalid login credentials']);
        // }

        $request->validate([
            'password' => 'required',
        ]);


        if (
            Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->password]) ||
            Auth::guard('web')->attempt(['username' => $user->username, 'password' => $request->password])
        ) {
            Auth::loginUsingId($user->id);
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withErrors(['password' => 'Invalid login credentials']);
        }
    }



    public function signout()
    {
        Auth::logout();

        return redirect()->route('auth');
    }
}
