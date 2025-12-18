<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Matiere;

class AdminForumController extends Controller
{
    // Lister tous les forums
    public function index()
    {
        return Forum::all();
    }

    // Créer un forum global
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'matiere_nom' => 'required|string|exists:matieres,nom'
        ]);

        // Récupérer la matière par son nom
        $matiere = Matiere::where('nom', $request->matiere_nom)->first();

        $forum = Forum::create([
            'titre' => $request->titre,
            'matiere_id' => $matiere->id,
            'created_by' => auth()->id()
        ]);

        return response()->json($forum);
    }

    // Supprimer un forum
    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);
        $forum->delete();

        return response()->json(['message' => 'Forum supprimé']);
    }
}
