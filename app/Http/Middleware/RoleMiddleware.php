<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Si aucun utilisateur authentifié
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        
        // Convertir les rôles en tableau d'entiers
        $allowedRoles = array_map('intval', $roles);
        
        // Vérifier si le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (!in_array($request->user()->role_id, $allowedRoles)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        
        return $next($request);
    }
}
