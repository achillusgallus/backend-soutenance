<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    // Liste des quiz pour l'élève
    public function index(Request $request)
    {
        $matieresIds = $request->user()->matieresEleve->pluck('id');

        $quiz = Quiz::whereIn('matiere_id', $matieresIds)
            ->with('matiere')
            ->get();

        return response()->json($quiz);
    }

    // Voir un quiz spécifique
    public function show(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        // Vérifier que l'élève a accès à la matière
        if (!$request->user()->matieresEleve->contains($quiz->matiere_id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json($quiz);
    }
}
