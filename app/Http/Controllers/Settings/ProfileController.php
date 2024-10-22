<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index($id)
    {
        $data = [
            'user' => User::find($id),
            'type_menu' => '',
            'page_name' => 'Update Profile',
        ];
        return view('pages.settings.profile.index', $data);
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required|string|max:255', 
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id), // Mengabaikan user dengan ID yang sedang di-edit
            ],
            'password' => 'nullable|string|min:6|max:255|confirmed', 
            'password_confirmation' => 'nullable|string|min:6|max:255', 
        ]);
            $user = User::find($id);
            //cek req null pass
            if ($request->password == null || $request->password_confirmation == null) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $user->password,
                    'username' => $user->name
                ]);
            } else {
                $user->update([
                    'name' => $request->name,
                    'username' => $user->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

            }
            return redirect()->back()->with(['status' => 'Success!', 'message' => 'Berhasil Update Data!']);
       
    }
}
