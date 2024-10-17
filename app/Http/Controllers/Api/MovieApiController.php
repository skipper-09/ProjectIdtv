<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    public function index(Request $request)
    {

        $id = $request->input('id');
        $title = $request->input('title');

        if ($id) {
            $movie = Movie::with('genre')->find($id);
            if ($movie) {
                return ResponseFormatter::success($movie, 'Movie berhasil diambil');
            } else {
                return ResponseFormatter::error($movie, 'Movie tidak ada', 400);
            }
        }

        $movie = Movie::with('genre');
        if ($title) {
            $movie->where('name', 'like', '%' . $title . '%');
        }

        return ResponseFormatter::success($movie->get(), 'Movie berhasil diambil');
    }
}
