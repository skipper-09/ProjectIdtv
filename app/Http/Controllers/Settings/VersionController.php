<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VersionControl;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index()
    {
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Version Control',
        ];
        return view('pages.settings.versioncontrol.index', $data);
    }



    public function getData()
    {
        $version = VersionControl::orderBy('id', 'desc')->get();

        return DataTables::of($version)->addIndexColumn()->addColumn('action', function ($user) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-version_control')) {
                $button .= ' <a href="' . route('versioncontrol.edit', ['id' => $user->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $user->id . ' data-type="edit"><i class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-version_control')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $user->id . ' data-type="delete" data-route="' . route('versioncontrol.delete', ['id' => $user->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }



    public function create()
    {
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Version Control',
        ];

        return view('pages.settings.versioncontrol.add', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'version' => 'required',
            'apk_url' => 'required',
            'release_note' => 'required'
        ]);
        VersionControl::create([
            'version' => $request->version,
            'apk_url' => $request->apk_url,
            'release_note' => $request->release_note
        ]);
        return redirect()->route(route: 'versioncontrol')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Version Control!']);
    }


    public function show($id)
    {
        $version = VersionControl::findOrFail($id);
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Version Control',
            'version' => $version
        ];

        return view('pages.settings.versioncontrol.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $version = VersionControl::findOrFail($id);
        $request->validate([
            'version' => 'required',
            'apk_url' => 'required',
            'release_note' => 'required'
        ]);
        $version->update([
            'version' => $request->version,
            'apk_url' => $request->apk_url,
            'release_note' => $request->release_note
        ]);
        return redirect()->route(route: 'versioncontrol')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan Version Control!']);
    }

    public function destroy($id)
    {
        try {
            $version = VersionControl::findOrFail($id);
            $version->delete();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Version Control Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

}
