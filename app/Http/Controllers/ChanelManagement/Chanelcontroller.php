<?php

namespace App\Http\Controllers\ChanelManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChanelRequest;
use App\Models\Chanel;
use App\Models\Categori;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\TryCatch;

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


    public function stream($replaceurl)
    {
        $channel = Chanel::where('replacement_url', $replaceurl)->firstOrFail();

        return redirect($channel->url);
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


    public function create()
    {
        $data = [
            'type_menu' => '',
            'page_name' => 'Tambah Chanel',
            'categori' => Categori::all(),

        ];
        return view('pages.chanel.chanel-management.addchanel', $data);
    }

    public function store(ChanelRequest $request)
    {
        $filename = '';
        if ($request->hasFile('logo')) {
            $file  = $request->file('logo');
            $filename = 'chanel_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images/chanel/'), $filename);
        }
        Chanel::create([
            'name' => $request->name,
            'url' => $request->url,
            'categori_id' => $request->input('categori_id'),
            'logo' => $filename,
            'type' => $request->type,
            'user_agent' => $request->user_agent,
            'security_type' => $request->input('security_type'),
            'security' => $request->security,
        ]);
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
        return view('pages.chanel.chanel-management.editchanel', $data);
    }

    public function update(Chanel $chanel, ChanelRequest $request, $id)
    {
        try {
            $datachanel = $chanel->find($id);
            $filename = '';
            if ($request->hasFile('logo')) {
                $file  = $request->file('logo');
                $filename = 'chanel_' . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/chanel/'), $filename);
                if ($datachanel->logo !== 'default.png') {
                    if (file_exists(public_path('storage/images/chanel/' . $datachanel->logo))) {
                        File::delete(public_path('storage/images/chanel/' . $datachanel->logo));
                    }
                }
            } else {
                $filename = $datachanel->logo;
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

            Chanel::where('id', $id)->delete();
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
}
