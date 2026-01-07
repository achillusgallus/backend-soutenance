<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index() { return User::with('roles')->get(); }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'surname'  => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'classe'   => 'nullable|in:tle_D,tle_A4,tle_C,pre_D,pre_A4,pre_C,troisieme',
            'role_id'  => 'required|integer|in:1,2,3',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'surname'     => $request->surname,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'classe'   => $request->classe, // peut être null
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->attach($role);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'sometimes|required|string',
            'surname'  => 'sometimes|required|string',
            'email'    => 'sometimes|required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6',
            'classe'   => 'nullable|in:tle_D,tle_A4,tle_C,pre_D,pre_A4,pre_C,troisieme',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('surname')) {
            $user->surname = $request->surname;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('classe')) {
            $user->classe = $request->classe;
        }

        $user->save();

        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            $user->roles()->sync([$role->id]);
        }

        return response()->json($user);
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message'=>'Utilisateur supprimé']);
    }
}
