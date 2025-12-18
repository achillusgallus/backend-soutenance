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
        ]);

        $user = User::create([
            'name'     => $request->name,
            'surname'     => $request->surname,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2,
            'classe'   => $request->classe, // peut être null
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->attach($role);

        return response()->json($user);
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message'=>'Utilisateur supprimé']);
    }
}
