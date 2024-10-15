<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VersionControl;
use Illuminate\Http\Request;

class VersionAplication extends Controller
{
    public function VersionLatest()
    {
        
        $latestUpdate = VersionControl::orderBy('created_at', 'desc')->first();

        // Jika tidak ada pembaruan, berikan pesan default
        if (!$latestUpdate) {
            return response()->json([
                'message' => 'No update available'
            ], 404);
        }

        // Kembalikan versi terbaru
        return response()->json([
            'message'=> 'Success Berhasil Mendapatkan Versi Aplikasi',
            'data'=> $latestUpdate
        ]);
    }
}
