<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $data = [
            'role' => Role::all(),
            'type_menu' => 'setting',
            'page_name' => 'Role',
        ];
        return view('pages.settings.role.index', $data);
    }

    public function getData()
    {
        $role = Role::all()->where('name', '!=', 'Developer');
        return DataTables::of($role)->addIndexColumn()->addColumn('action', function ($role) {
            $userauth = User::with('roles')->where('id', Auth::id())->first();
            $button = '';
            if ($userauth->can('update-role')) {
                $button .= ' <a href="' . route('role.edit', ['id' => $role->id]) . '" class="btn btn-sm btn-success action mr-1" data-id=' . $role->id . ' data-type="edit"><i class="fa-solid fa-pencil"></i></a>';
            }
            if ($userauth->can('delete-role')) {
                $button .= ' <button class="btn btn-sm btn-danger action" data-id=' . $role->id . ' data-type="delete" data-route="' . route('role.delete', ['id' => $role->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            }
            return '<div class="d-flex">' . $button . '</div>';
        })->make(true);
    }
}
