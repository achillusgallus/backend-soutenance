<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResultatsQuiz;

class ResultatsQuizController extends Controller
{
    // Enregistrer le résultat d'un quiz
    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quiz,id',
            'score' => 'required|numeric|min:0',
        ]);

        $resultat = ResultatsQuiz::updateOrCreate(
            [
                'quiz_id' => $request->quiz_id,
                'eleve_id' => $request->user()->id,
            ],
            ['score' => $request->score]
        );

        return response()->json($resultat);
    }

    // Récupérer tous les résultats de l'élève
    public function index(Request $request)
    {
        $resultats = $request->user()->resultatsQuiz()->with('quiz')->get();
        return response()->json($resultats);
    }
}
