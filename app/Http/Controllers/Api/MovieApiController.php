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
            $movie = Movie::with(['genre','episode'])->find($id);
            if ($movie) {
                return ResponseFormatter::success($movie, 'Movie berhasil diambil');
            } else {
                return ResponseFormatter::error($movie, 'Movie tidak ada', 400);
            }
        }

        $movie = Movie::with(['genre','episode']);
        if ($title) {
            $movie->where('name', 'like', '%' . $title . '%');
        }

        return ResponseFormatter::success($movie->get(), 'Movie berhasil diambil');
    }



    //get genre
    public function getgenre(Request $request)
    {

        $id = $request->input('id');
        $name = $request->input('name');

        if ($id) {
            $movie = Genre::with('movie')->find($id);
            if ($movie) {
                return ResponseFormatter::success($movie, 'Genre berhasil diambil');
            } else {
                return ResponseFormatter::error($movie, 'Genre tidak ada', 400);
            }
        }

        $movie = Genre::with('movie');
        if ($name) {
            $movie->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success($movie->get(), 'Genre berhasil diambil');
    }
}
