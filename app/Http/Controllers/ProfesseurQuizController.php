<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class ProfesseurQuizController extends Controller
{
    public function index(Request $request)
    {
        $quiz = Quiz::where('professeur_id', $request->user()->id)
            ->with('matiere')
            ->get();
        return response()->json($quiz);
    }

    public function store(Request $request)
    {
        $request->validate([
            'matiere_nom' => 'required|string|max:255', // on valide le nom de la matière
            'titre' => 'required|string|max:255',
            'duree' => 'required|integer',
        ]);

        // On recherche la matière par son nom
        $matiere = \App\Models\Matiere::where('nom', $request->matiere_nom)->first();

        if (!$matiere) {
            return response()->json([
                'error' => 'La matière spécifiée est introuvable.'
            ], 404);
        }

        $quiz = \App\Models\Quiz::create([
            'matiere_id' => $matiere->id, // on enregistre l'id trouvé
            'professeur_id' => $request->user()->id,
            'titre' => $request->titre,
            'duree' => $request->duree,
        ]);

        return response()->json($quiz);
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::where('professeur_id', $request->user()->id)->findOrFail($id);
        $quiz->update($request->only(['titre','duree']));
        return response()->json($quiz);
    }

    public function destroy(Request $request, $id)
    {
        $quiz = Quiz::where('professeur_id', $request->user()->id)->findOrFail($id);
        $quiz->delete();
        return response()->json(['message'=>'Quiz supprimé']);
    }
}
