<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cours;

class CoursController extends Controller
{
    // Liste des cours pour l'élève
    public function index(Request $request)
    {
        $user = $request->user();

        // Les matières de l'élève
        $matieresIds = $user->matieresEleve->pluck('id');

        // Tous les cours liés à ses matières
        $cours = Cours::whereIn('matiere_id', $matieresIds)
            ->with('matiere', 'professeur')
            ->get();

        return response()->json($cours);
    }

    // Voir un cours spécifique
    public function show(Request $request, $id)
    {
        $cours = Cours::with('matiere', 'professeur')->findOrFail($id);

        // Vérifier que l'élève a accès
        if (!$request->user()->matieresEleve->contains($cours->matiere_id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json($cours);
    }
}
