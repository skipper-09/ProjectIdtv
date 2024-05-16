<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(){
        $data = [
            'role' => Role::all(),
            'type_menu' => 'setting',
            'page_name' => 'Role',
        ];
        return view('pages.settings.role.index', $data);
    }

    public function getData()
    {
        $role = Role::all()->where('name','!=','Developer');
        return DataTables::of($role)->addIndexColumn()->addColumn('action', function ($role) {
            $edit = ' <a href="' . route('role.edit', ['id' => $role->id]) . '" class="btn btn-sm btn-success action" data-id=' . $role->id . ' data-type="edit"><i
                                                            class="fa-solid fa-pencil"></i></a>';
            $delete = ' <button class="btn btn-sm btn-danger action" data-id=' . $role->id . ' data-type="delete" data-route="' . route('role.delete', ['id' => $role->id]) . '"><i
                                                            class="fa-solid fa-trash"></i></button>';
            return $edit . $delete;
        })->make(true);
    }
   

}
