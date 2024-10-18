<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class EpisodeController extends Controller
{
    public function index($movie_id)
    {


        if ($movie_id) {
            // Fetch episodes with the related movie based on movie_id

            $episodes = Movie::find($movie_id);

            // Access the first episode to get the movie title (if episodes exist)


            $data = [
                'type_menu' => 'movie',
                'page_name' => $episodes->title,
                'movie_id' => $movie_id, // Pass the movie ID to the view
            ];

            return view('pages.movie.episode.index', $data);
        }


        // Pass movie_id as null (since it's not set in this case)
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Episode',
            'movie_id' => null, // Ensuring movie_id is always defined
        ];

        return view('pages.movie.episode.index', $data);
    }



    public function getData($movie_id)
    {
        $episodes = Episode::with(['movie'])->where('movie_id', $movie_id)->get();
        return DataTables::of($episodes)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-episode')) {
                $button .= ' <a href="' . route('episode.edit', ['movie_id'=>$data->movie_id,'id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('read-episode-player')) {
                $button .= ' <a href="' . route('episode.player', ['movie_id'=>$data->movie_id,'id' => $data->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-eye"></i></a>';
            }
            if ($userauth->can('delete-episode')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('episode.delete', ['movie_id'=>$data->movie_id,'id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->editColumn('movie', function ($data) {
            return $data->movie->title;
        })->editColumn('cover_image', function ($data) {
            $urlimage = asset("storage/images/movie/$data->cover_image");
            return '<img src="' . $urlimage . '" border="0" 
        height="80" class="img-rounded" align="center" />';
        })->rawColumns(['movie', 'cover_image', 'action'])->make(true);
    }


    public function create($movie_id)
    {
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Tambah Episode',
            'movie' => Movie::find($movie_id)
        ];
        return view('pages.movie.episode.add', $data);
    }


    public function store($movie_id, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'episode_number' => 'required',
            'cover_image' => 'required',
            'url' => 'required',
            'status' => 'required'
        ]);


        $filename = '';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'cover_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/movie/'), $filename);
        }
        Episode::create(
            [
                'movie_id' => $movie_id,
                'title' => $request->title,
                'episode_number' => $request->episode_number,
                'cover_image' => $filename,
                'url' => $request->url,
                'status' => $request->status
            ]
        );

        return redirect()->route('episode', ['movie_id' => $movie_id])->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Episode!']);
    }





    public function show($movie_id,$id)
    {
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Edit Episode',
            'episode' => Episode::find($id)
        ];
        return view('pages.movie.episode.edit', $data);
    }



    public function update($movie_id, Request $request,$id)
    {
        $request->validate([
            'title' => 'required|string',
            'episode_number' => 'required',
            'url' => 'required',
            'status' => 'required'
        ]);


        $dataepisode = Episode::findOrFail($id);
            $filename = $dataepisode->cover_image;

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filename = 'movie_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/movie/'), $filename);
                if ($dataepisode->cover_image !== 'default.png' && file_exists(public_path('storage/images/movie/' . $dataepisode->cover_image))) {
                    File::delete(public_path('storage/images/movie/' . $dataepisode->cover_image));
                }
            }
            $dataepisode->update(
            [
                'movie_id' => $movie_id,
                'title' => $request->title,
                'episode_number' => $request->episode_number,
                'cover_image' => $filename,
                'url' => $request->url,
                'status' => $request->status
            ]
        );

        return redirect()->route('episode', ['movie_id' => $movie_id])->with(['status' => 'Success!', 'message' => 'Berhasil Update Episode!']);
    }


    public function destroy($movie_id,$id)
    {
        try {
            $movie = Episode::where('id', $id)->first();
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

            $chanel = Episode::findOrFail($id);
            $chanel->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Episode Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }



    public function Player($movie_id,$id)
    {
        try {
            $movie = Episode::find($id);
            
            $data = [
                'type_menu' => 'movie',
                'page_name' => $movie->title,
                'player' => $movie->url,
            ];

            return view('pages.movie.episode.player', $data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }
}
