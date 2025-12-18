<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Matiere;

class ProfesseurCoursController extends Controller
{
    // Lister les cours du professeur
    public function index(Request $request)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)
            ->with('matiere')
            ->get();

        return response()->json($cours);
    }

    // Créer un cours
    public function store(Request $request)
    {
        $request->validate([
            'matiere_nom' => 'required|string|max:255', // on valide le nom de la matière
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        // On récupère la matière par son nom
        $matiere = Matiere::where('nom', $request->matiere_nom)->first();

        if (!$matiere) {
            return response()->json([
                'error' => 'La matière spécifiée est introuvable.'
            ], 404);
        }

        $cours = Cours::create([
            'matiere_id' => $matiere->id, // on enregistre l'id trouvé
            'professeur_id' => $request->user()->id,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
        ]);

        return response()->json($cours);
    }

    // Modifier un cours
    public function update(Request $request, $id)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)->findOrFail($id);

        $cours->update($request->only(['titre','contenu']));

        return response()->json($cours);
    }

    // Supprimer un cours
    public function destroy(Request $request, $id)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)->findOrFail($id);
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé']);
    }
}
