<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'user' => User::all(),
            'type_menu' => 'setting',
            'page_name' => 'User',
        ];
        return view('pages.settings.user.index', $data);
    }
    public function getData()
    {
        $user = User::with('roles')->where('name', '!=', 'Developer')->get();
        return DataTables::of($user)->addIndexColumn()->addColumn('action', function ($user) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-users')) {
                $button .= ' <a href="' . route('user.edit', ['id' => $user->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $user->id . ' data-type="edit"><i class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-users')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $user->id . ' data-type="delete" data-route="' . route('user.delete', ['id' => $user->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }

    public function create()
    {
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Tambah User',
            'role'=> Role::where('name','!=','Developer')->get()
        ];
        return view('pages.settings.user.adduser', $data);
    }
}
