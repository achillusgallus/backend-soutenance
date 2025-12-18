<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;

class ProfesseurQuestionController extends Controller
{
    // Lister les questions d'un quiz
    public function index(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);

        // Vérifier que le quiz appartient au professeur
        if ($quiz->professeur_id != $request->user()->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $questions = $quiz->questions()->get();
        return response()->json($questions);
    }

    // Créer une question
    public function store(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);

        if ($quiz->professeur_id != $request->user()->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:qcm,texte'
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question' => $request->question,
            'type' => $request->type
        ]);

        return response()->json($question);
    }

    // Modifier une question
    public function update(Request $request, $quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($question_id);

        if ($quiz->professeur_id != $request->user()->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'question' => 'sometimes|string',
            'type' => 'sometimes|in:qcm,texte'
        ]);

        $question->update($request->only(['question','type']));

        return response()->json($question);
    }

    // Supprimer une question
    public function destroy(Request $request, $quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($question_id);

        if ($quiz->professeur_id != $request->user()->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $question->delete();
        return response()->json(['message' => 'Question supprimée']);
    }
}
