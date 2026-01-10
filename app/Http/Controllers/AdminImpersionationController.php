<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminImpersonationController extends Controller
{
    public function impersonate(Request $request, $id)
    {
        $admin = $request->user();

        // Vérifier que l'utilisateur est bien admin
        if ($admin->role_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Trouver le professeur
        $teacher = User::where('id', $id)->where('role_id', 2)->first();
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        // Créer un token Sanctum pour le professeur
        $token = $teacher->createToken('impersonation')->plainTextToken;

        return response()->json([
            'impersonation_token' => $token,
            'teacher' => $teacher,
        ]);
    }

    public function stop(Request $request)
    {
        // Ici tu peux simplement dire au front de réutiliser le token admin
        return response()->json(['message' => 'Impersonation stopped']);
    }
}
