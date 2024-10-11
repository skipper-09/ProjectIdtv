<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_id' => 'required'
        ]);

        // Retrieve the user by username
        $user = Customer::where('username', $request->username)->first();

        // If the user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {

            // Check if the user is already logged in on another device
            if ($user->device_id && $user->device_id !== $request->device_id) {
                return response()->json(['error' => 'Already logged in on another device'], 403);
            }

            // Update device_id to allow login on this device
            $user->update(['device_id' => $request->device_id]);

            // Fetch the user's latest subscription data
            $data = Customer::where('id', $user->id)
                ->whereHas('subcrib', function ($query) {
                    $query->orderBy('end_date', 'asc');
                })
                ->with([
                    'subcrib' => function ($query) {
                        $query->orderBy('end_date', 'desc')
                            ->limit(1); // Get only the latest subscription
                    }
                ])->first();

            // Generate Sanctum token
            $token = $user->createToken(uniqid())->plainTextToken;

            // Return successful login response
            return response()->json([
                'message' => 'Successfully logged in Customer Account',
                'data' => $data,
                'token' => $token,
            ]);
        } else {
            // If the credentials are wrong, return an error
            return response()->json([
                'message' => 'Login failed, Username or Password incorrect',
            ], 401);
        }
    }


    public function logout(Request $request)
{
    // Get the authenticated user from the request
    $user = $request->user();

    if ($user) {
        // Reset device_id to allow login on another device
        $user->update(['device_id' => null]);

        // Delete the current access token (Sanctum token)
        $user->currentAccessToken()->delete();

        // Return a successful logout response
        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    // Return error response if the user was not found
    return response()->json([
        'error' => 'Unable to logout, user not found',
    ], 404);
}



public function checkDevice(Request $request)
    {

        $user = $request->user();

    

        if ($request->device_id == null) {
            return response()->json(['message'=>'error device_id tidak ada']);
        }
    

        // Cek apakah device_id sesuai dengan yang tersimpan di database
        if ($user->device_id !== $request->device_id) {
            return response()->json(['message' => 'Unauthorized device.'], 401);
        }

        return response()->json(['message' => 'Device is valid.'],200);
    }
}
