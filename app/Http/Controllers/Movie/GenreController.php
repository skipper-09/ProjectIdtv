<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class GenreController extends Controller
{
    public function index()
    {
        $data = [

            'type_menu' => 'movie',
            'page_name' => 'Genre',

        ];
        return view('pages.movie.genre.index', $data);
    }


    public function getData()
    {
        $genre = Genre::query()->orderByDesc('created_at')->get();
        return DataTables::of($genre)->addIndexColumn()->addColumn('action', function ($data) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-genre')) {
                $button .= ' <a href="' . route('genre.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $data->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-genre')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $data->id . ' data-type="delete" data-route="' . route('genre.delete', ['id' => $data->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }


    public function create()
    {
        $data = [
            'type_menu' => 'movie',
            'page_name' => 'Tambah Genre',
        ];
        return view('pages.movie.genre.add', $data);
    }


    public function store(GenreRequest $request)
    {
        $data = $request->validated();
        Genre::create($data);
        return redirect()->route('genre')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Genre!']);
    }

    public function show(Genre $genre, $id)
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Edit Genre',
            'genre' => $genre->find($id)
        ];
        return view('pages.movie.genre.edit', $data);
    }

    public function update(GenreRequest $request, $id)
    {
        $request->validated();
        $genre = Genre::find($id);

        $genre->update([
            'name' => $request->name
        ]);
        return redirect()->route('genre')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Genre!']);
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();

            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Genre Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Genre Tidak Bisa Dihapus Karena Masih digunakan oleh Chanel!',
                'trace' => $e->getTrace()
            ]);
        }
    }

}
