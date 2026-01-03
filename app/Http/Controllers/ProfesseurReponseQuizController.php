<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponse;

class ProfesseurReponseQuizController extends Controller
{
    /**
     * Liste toutes les réponses pour une question donnée.
     */
    public function index(Request $request, $question_id)
    {
        $reponses = Reponse::where('question_id', $question_id)->get();
        return response()->json($reponses);
    }

    /**
     * Stocke une nouvelle réponse pour une question.
     */
    public function store(Request $request, $question_id)
    {
        $data = $request->validate([
            'reponse' => 'required|string',
            'est_correcte' => 'nullable|boolean'
        ]);

        $data['question_id'] = $question_id;

        $reponse = Reponse::create($data);

        return response()->json($reponse, 201);
    }

    /**
     * Affiche une réponse spécifique.
     */
    public function show($id)
    {
        $reponse = Reponse::findOrFail($id);
        return response()->json($reponse);
    }

    /**
     * Met à jour une réponse existante.
     */
    public function update(Request $request, $id)
    {
        $reponse = Reponse::findOrFail($id);

        $data = $request->validate([
            'reponse' => 'sometimes|required|string',
            'est_correcte' => 'nullable|boolean'
        ]);

        $reponse->update($data);

        return response()->json($reponse);
    }

    /**
     * Supprime une réponse.
     */
    public function destroy($id)
    {
        $reponse = Reponse::findOrFail($id);
        $reponse->delete();
        return response()->json(['message' => 'Réponse supprimée']);
    }
}
