<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Exports\ChanelExport;
use App\Http\Controllers\Controller;

use App\Imports\ChanelImport;
use App\Models\Chanel;
use App\Models\Categori;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;


class Chanelcontroller extends Controller
{
    public function index(): View
    {
        $data = [
            'chanel' => Chanel::all(),
            'type_menu' => 'layout',
            'page_name' => 'Chanel',
            'route' => 'chanel'
        ];
        return view('pages.chanel.chanel-management.index', $data);
    }



    public function stream(Request $request, $replaceurl, $path = null)
    {
        $channel = Chanel::where('replacement_url', $replaceurl)->firstOrFail();

        // URL asli yang ingin kita redirect
        $originalUrl = $channel->url;

        // Logging untuk debugging
        \Log::info('Redirecting to URL: ' . $originalUrl);

        // Kirimkan redirect dengan CORS headers untuk memastikan player tidak diblokir
        return redirect($originalUrl)
            ->withHeaders([
                'Access-Control-Allow-Origin' => '*', // Mengizinkan semua domain
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, X-Auth-Token',
            ]);
    }


    public function getData()
    {
        $chanel = Chanel::query()->with('categori')->get();
        return DataTables::of($chanel)->addIndexColumn()->addColumn('action', function ($chanel) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-chanel')) {
                $button .= ' <a href="' . route('chanel.edit', ['id' => $chanel->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $chanel->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('read-chanel-player')) {
                $button .= ' <a href="' . route('chanel.player', ['id' => $chanel->id]) . '" class="btn btn-sm btn-primary action mr-1" data-id=' . $chanel->id . ' data-type="edit"><i
                                                            class="fa-solid fa-eye"></i></a>';
            }
            if ($userauth->can('delete-chanel')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $chanel->id . ' data-type="delete" data-route="' . route('chanel.delete', ['id' => $chanel->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->addColumn('logo', function ($chanel) {
            $urlimage = asset("storage/images/chanel/$chanel->logo");
            return '<img src="' . $urlimage . '" border="0" 
        width="40" class="img-rounded" align="center" />';
        })->addColumn('is_active', function ($chanel) {
            $active = '';
            $chanel->is_active == 1 ? $active = '<span class="badge badge-primary">Aktif</span>' : $active = '<span class="badge badge-secondary">Tidak Aktif</span>';
            return $active;
        })->editColumn('type', function ($chanel) {
            $active = '';
            $chanel->type == "m3u" ? $active = '<span class="badge badge-success">m3u</span>' : $active = '<span class="badge badge-warning">mpd</span>';
            return $active;
        })->editColumn('categori', function (Chanel $chanel) {
            return $chanel->categori->name;
        })->rawColumns(['logo', 'action', 'is_active', 'categori', 'type'])->make(true);
    }



    public function vidiochanel($id)
    {
        try {
            $film = Chanel::find($id);
            $data = [
                'type_menu' => 'layout',
                'page_name' => $film->name,
                'player' => url("/stream/$film->replacement_url"),
            ];

            return view('pages.chanel.chanel-management.detailplayer', $data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Chanel',
            'categori' => Categori::all(),

        ];
        return view('pages.chanel.chanel-management.addchanel', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'categori_id' => 'required|exists:categoris,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'type' => 'required|in:m3u,mpd',
            'user_agent' => 'nullable|string|max:255',
            'security_type' => 'nullable|in:clearkey,widevine',
            'security' => 'nullable|string|max:255',
        ]);

        $filename = '';
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'chanel_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/chanel/'), $filename);
        }

        Chanel::create(
            [
                'name' => $request->name,
                'url' => $request->url,
                'categori_id' => $request->input('categori_id'),
                'logo' => $filename,
                'type' => $request->type,
                'user_agent' => $request->user_agent,
                'security_type' => $request->input('security_type'),
                'security' => $request->security,
            ]
        );

        return redirect()->route('chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Chanel!']);
    }

    public function show(Chanel $chanel, $id)
    {
        $data = [
            'chanel' => $chanel->find($id),
            'type_menu' => 'layout',
            'page_name' => 'Edit Chanel',
            'categori' => Categori::all()

        ];
        return response()->view('pages.chanel.chanel-management.editchanel', $data);
    }

    // public function update(Chanel $chanel, ChanelRequest $request, $id)
    // {
    //     try {
    //         $datachanel = $chanel->find($id);
    //         $filename = '';
    //         if ($request->hasFile('logo')) {
    //             $file = $request->file('logo');
    //             $filename = 'chanel_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('storage/images/chanel/'), $filename);
    //             if ($datachanel->logo !== 'default.png') {
    //                 if (file_exists(public_path('storage/images/chanel/' . $datachanel->logo))) {
    //                     File::delete(public_path('storage/images/chanel/' . $datachanel->logo));
    //                 }
    //             }
    //         } else {
    //             $filename = $datachanel->logo;
    //         }

    //         $datachanel->update([
    //             'name' => $request->name,
    //             'url' => $request->url,
    //             'categori_id' => $request->input('categori_id'),
    //             'logo' => $filename,
    //             'type' => $request->type,
    //             'user_agent' => $request->user_agent,
    //             'security_type' => $request->input('security_type'),
    //             'security' => $request->security,
    //         ]);
    //         return redirect()->route('chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Chanel!']);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTrace()
    //         ]);
    //     }
    // }

    public function update(Chanel $chanel, Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'categori_id' => 'required|exists:categoris,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'type' => 'required|in:m3u,mpd',
            'user_agent' => 'nullable|string|max:255',
            'security_type' => 'nullable|in:clearkey,widevine',
            'security' => 'nullable|string|max:255',
        ]);

        try {
            $datachanel = $chanel::findOrFail($id);
            $filename = $datachanel->logo;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'chanel_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/chanel/'), $filename);
                if ($datachanel->logo !== 'default.png' && file_exists(public_path('storage/images/chanel/' . $datachanel->logo))) {
                    File::delete(public_path('storage/images/chanel/' . $datachanel->logo));
                }
            }

            $datachanel->update([
                'name' => $request->name,
                'url' => $request->url,
                'categori_id' => $request->input('categori_id'),
                'logo' => $filename,
                'type' => $request->type,
                'user_agent' => $request->user_agent,
                'security_type' => $request->input('security_type'),
                'security' => $request->security,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('chanel')->with(['status' => 'Success!', 'message' => 'Berhasil Mengubah Chanel!']);
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
            $chanel = Chanel::where('id', $id)->first();
            if (!$chanel) {
                return response()->json([
                    'status' => '500',
                    'error' => 'Chanel Kosong!'
                ]);
            }
            if ($chanel->logo !== 'default.png') {
                if (file_exists(public_path('storage/images/chanel/' . $chanel->logo))) {
                    File::delete(public_path('storage/images/chanel/' . $chanel->logo));
                }
            }

            $chanel = Chanel::findOrFail($id);
            $chanel->delete();
            //return response
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Data Chanel Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }




//export
    public function export() 
    {
        return Excel::download(new ChanelExport, 'chanel.csv');
    }





//import
public function ImportChanel(Request $request)
{
    \Log::info('Start import process');

    // $request->validate([
    //     'file' => 'required|mimes:xlsx,xls,csv',
    // ]);

    \Log::info('Validation passed');

    try {
        Excel::import(new ChanelImport, $request->file('file'));

        \Log::info('Import successful');

        return redirect()->back()->with(['status' => 'Success!', 'message' => 'Berhasil Import Chanel!']);
    } catch (Exception $e) {
        \Log::error('Error during import: ' . $e->getMessage());

        return response()->json([
            'message' => 'Error occurred during import: ' . $e->getMessage(),
            'trace' => $e->getTrace()
        ]);
    }
}
}
