<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MovieController extends Controller
{
    public function index()
    {
        $data = [

            'type_menu' => 'movie',
            'page_name' => 'Movie',

        ];
        return view('pages.movie.movie-management.index', $data);
    }


    public function getData()
    {
        $movie = Movie::query()->orderByDesc('created_at')->get();
        return DataTables::of($movie)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-movie')) {
                $button .= ' <a href="' . route('movie.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-movie')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('movie.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Tambah Movie',
            'genre' => Genre::all()
        ];
        return view('pages.movie.movie-management.add', $data);
    }


    public function store(Request $request)
    {
    // dd($request);
        $request->validate([
            'genre_id' => 'required', 
            'title' => 'required|string',
            'rating' => 'required|numeric|min:0|max:10|regex:/^\d+(\.\d{1})?$/',
            'cover_image' => 'required', // Validate file is an image
            'type' => 'required'
        ]);


        $filename = '';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'cover_' . rand(0, 999999999). '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/movie/'), $filename);
        }
        Movie::create(
            [
                'genre_id'=>$request->genre_id,
                'title'=>$request->title,
                'rating'=>$request->rating,
                'cover_image'=>$filename,
                'type'=>$request->type,
                'url'=>$request->url,
            ]
        );

        return redirect()->route('movie')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Movie!']);
    }


    public function destroy($id)
    {
        try {
            $movie = Movie::where('id', $id)->first();
            if (!$movie) {
                return response()->json([
                    'status' => '500',
                    'error' => 'Movie Kosong!'
                ]);
            }
            if ($movie->cover_image !== 'default.png') {
                if (file_exists(public_path('storage/images/movie/' . $movie->cover_image))) {
                    File::delete(public_path('storage/images/movie/' . $movie->cover_image));
                }
            }

            $chanel = Movie::findOrFail($id);
            $chanel->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Movie Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }



}
