<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SujetsForum;

class SujetsForumController extends Controller
{
    // Lister les sujets d'un forum
    public function index(Request $request, $forum_id)
    {
        $sujets = SujetsForum::where('forum_id', $forum_id)
            ->with('auteur')
            ->get();

        return response()->json($sujets);
    }

    // Créer un sujet (élève)
    public function store(Request $request)
    {
        $request->validate([
            'forum_id' => 'required|exists:forums,id',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        $sujet = SujetsForum::create([
            'forum_id' => $request->forum_id,
            'user_id' => $request->user()->id,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
        ]);

        return response()->json($sujet);
    }
}
