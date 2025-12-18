<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;

class ForumController extends Controller
{
    // Lister les forums accessibles à l'élève
    public function index(Request $request)
    {
        $matieresIds = $request->user()->matieresEleve->pluck('id');

        $forums = Forum::whereIn('matiere_id', $matieresIds)->get();

        return response()->json($forums);
    }

    // Voir un forum spécifique
    public function show(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);

        if (!$request->user()->matieresEleve->contains($forum->matiere_id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json($forum);
    }
}
