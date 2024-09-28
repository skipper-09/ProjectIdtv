<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $user = User::with('roles')->whereNotIn('name', ['Developer'])->orderBy('id', 'desc')->get();

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
            'role' => Role::where('name', '!=', 'Developer')->get()
        ];
        return view('pages.settings.user.adduser', $data);
    }

    public function store(UserRequest $request)
    {
        $request->merge(['password' => Hash::make($request->password),]);

        User::create($request->only(['name', 'email', 'username', 'password']))->assignRole($request->role);
        return redirect()->route('user')->with(['status' => 'Success!', 'message' => 'Berhasil Menambahkan User!']);
    }


    public function show($id)
    {
        $data = [
            'type_menu' => 'setting',
            'page_name' => 'Edit User',
            'role' => Role::where('name', '!=', 'Developer')->get(),
            'user'=> User::find($id),
        ];
        return view('pages.settings.user.edituser', $data);
    }


    public function update(UserRequest $request,$id){
        try {
            $request->validated();
            $user= User::find($id);

            //cek in tabel model hasrole
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            //new assign role 
            $user->assignRole($request->role);
            //cek req null pass
            if ($request->password == null || $request->password_confirmation == null) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $user->password,
                    'username' => $request->username,
                ]);
            } else {
                $user->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                
            }
            return redirect()->route('user')->with(['status' => 'Success!', 'message' => 'Berhasil Update User!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'User Berhasil Dihapus!.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }
}
