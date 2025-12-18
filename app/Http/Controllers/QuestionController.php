<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    // Récupérer les questions d'un quiz spécifique
    public function index(Request $request, $quiz_id)
    {
        $user = $request->user();

        // Vérifier que l'élève a accès à la matière du quiz
        $questions = Question::where('quiz_id', $quiz_id)
            ->with('quiz.matiere')
            ->get()
            ->filter(function ($q) use ($user) {
                return $user->matieresEleve->contains($q->quiz->matiere_id);
            });

        return response()->json($questions);
    }
}
