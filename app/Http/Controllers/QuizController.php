<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    // Liste des quiz pour l'élève
    public function index(Request $request)
    {
        // 1. On récupère la classe de l'élève (ex: pre_D)
        $classeEleve = $request->user()->classe;
        // 2. On cherche les quiz dont la MATIÈRE appartient à cette même classe
        $quiz = Quiz::whereHas('matiere', function($query) use ($classeEleve) {
            $query->where('classe', $classeEleve);
        })
        ->with('matiere') // Pour avoir les infos de la matière dans Flutter
        ->get();
        return response()->json($quiz);
    }

    // Voir un quiz spécifique
    public function show(Request $request, $id)
    {
        // On charge le quiz avec ses questions et les réponses possibles
        $quiz = Quiz::with(['matiere', 'questions.reponses'])->findOrFail($id);
        // Vérifier l'accès via la classe de l'élève (plus robuste)
        if ($request->user()->classe !== $quiz->matiere->classe) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        return response()->json($quiz);
    }
}
