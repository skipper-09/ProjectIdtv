<?php

namespace App\Http\Controllers\Movie;

use App\Exports\MovieExport;
use App\Http\Controllers\Controller;
use App\Imports\MovieImport;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
            if ($userauth->can('read-movie-player')) {
                if ($data->type == 'movie') {
                    $button .= ' <a href="' . route('movie.player', ['id' => $data->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                    class="fa-solid fa-eye"></i></a>';
                } else {
                    $button .= ' <a href="' . route('episode', ['movie_id' => $data->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $data->id . ' data-type="Episode"><i
                                                                class="fa-solid fa-eye"></i></a>';
                }
            }
            if ($userauth->can('delete-movie')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('movie.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('genre', function ($data) {
            return $data->genre->name;
        })->editColumn('cover_image', function ($data) {
            $urlimage = asset("storage/images/movie/$data->cover_image");
            return '<img src="' . $urlimage . '" border="0" 
        height="80" class="img-rounded" align="center" />';
        })->rawColumns(['genre', 'cover_image', 'action'])->make(true);
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
            'type' => 'required',
        ]);


        $filename = '';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'cover_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/movie/'), $filename);
        }
        Movie::create(
            [
                'genre_id' => $request->genre_id,
                'title' => $request->title,
                'rating' => $request->rating,
                'cover_image' => $filename,
                'type' => $request->type,
                'url' => $request->url,
                'status' => $request->status,
                'description' => $request->description
            ]
        );

        return redirect()->route('movie')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Movie!']);
    }


    public function show($id)
    {
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Edit Movie',
            'genre' => Genre::all(),
            'movie' => Movie::findOrFail($id),
        ];
        return view('pages.movie.movie-management.edit', $data);
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'genre_id' => 'required',
            'title' => 'required|string',
            'rating' => 'required|numeric|min:0|max:10|regex:/^\d+(\.\d{1})?$/',
            'type' => 'required'
        ]);

        try {
            $datamovie = Movie::findOrFail($id);
            $filename = $datamovie->cover_image;

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filename = 'movie_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/movie/'), $filename);
                if ($datamovie->cover_image !== 'default.png' && file_exists(public_path('storage/images/movie/' . $datamovie->cover_image))) {
                    File::delete(public_path('storage/images/movie/' . $datamovie->cover_image));
                }
            }

            $datamovie->update([
                'genre_id' => $request->genre_id,
                'title' => $request->title,
                'rating' => $request->rating,
                'cover_image' => $filename,
                'type' => $request->type,
                'url' => $request->url,
                'status' => $request->status,
                'description' => $request->description
            ]);

            return redirect()->route('movie')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Movie!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
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



    public function Player($id)
    {
        try {
            $movie = Movie::find($id);
            $data = [
                'type_menu' => 'movie',
                'page_name' => $movie->title,
                'player' => $movie->url,
            ];

            return view('pages.movie.movie-management.player', $data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }



    public function export()
    {
        $now = now()->toDateString();
        return Excel::download(new MovieExport, "movie-$now.csv");
    }



    public function ImportMovie(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        // ]);

        try {
            Excel::import(new MovieImport, $request->file('file'));

            \Log::info('Import successful');

            return redirect()->back()->with(['status' => 'Success!', 'message' => 'Berhasil Import Movie!']);
        } catch (Exception $e) {
            \Log::error('Error during import: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error occurred during import: ' . $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }
}
