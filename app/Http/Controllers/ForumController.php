<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Matiere; // <--- IMPORTANT : N'oubliez pas cet import

class ForumController extends Controller
{
    // Lister les forums accessibles à l'élève
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Récupérer les IDs des matières associées à la classe de l'élève
        // On suppose que l'élève a une colonne 'classe' (ex: 'pre_D') et la matière aussi.
        $matieresIds = Matiere::where('classe', $user->classe)->pluck('id');

        // Si la méthode par classe ne marche pas, essayez l'ancienne méthode de secours :
        // $matieresIds = $user->matieresEleve->pluck('id');

        // 2. Récupérer les forums liés à ces matières
        $forums = Forum::whereIn('matiere_id', $matieresIds)
                       ->with('matiere') // Charge les infos utiles (matière, auteur du forum)
                       ->latest() // Trie par le plus récent
                       ->get();

        return response()->json($forums);
    }

    // Voir un forum spécifique
    public function show(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);
        $user = $request->user();

        // Vérification de sécurité : L'élève est-il dans la bonne classe pour voir ce forum ?
        $matiere = Matiere::find($forum->matiere_id);
        
        if ($matiere->classe !== $user->classe) {
             return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json($forum);
    }
}