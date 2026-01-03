<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;
use App\Models\User;



class StudentMatiereController extends Controller
{
    /**
     * Retourne les matières correspondant à la classe choisie par l'élève.
     * Reçoit le paramètre `classe` en query string ou body.
     */
    public function index(Request $request)
    {
        // Priorise la classe de l'utilisateur connecté, sinon accepte le paramètre `classe` en query
        $user = $request->user();
        $userClasse = $user->classe ?? null;

        $classe = $userClasse ?: $request->query('classe');

        if (!$classe) {
            return response()->json(['message' => 'Classe non définie'], 400);
        }

        $classe = trim($classe);

        // Choix du mode: exact (default) ou partiel (LIKE)
        $partial = filter_var($request->query('partial', false), FILTER_VALIDATE_BOOLEAN);

        $perPage = $request->query('per_page', 15);

        $query = Matiere::query();
        if ($partial) {
            $query->whereRaw('LOWER(classe) LIKE ?', ['%'.strtolower($classe).'%']);
        } else {
            $query->whereRaw('LOWER(classe) = ?', [strtolower($classe)]);
        }

        if ($perPage === 'all' || (int)$perPage <= 0) {
            $matieres = $query->get();
            return response()->json(['data' => $matieres]);
        }

        $matieres = $query->paginate((int)$perPage);

        return response()->json($matieres);
    }
}
