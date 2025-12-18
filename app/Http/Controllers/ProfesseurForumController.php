<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SujetsForum;
use App\Models\MessagesForum;

class ProfesseurForumController extends Controller
{
    // Lister les sujets des forums auxquels le prof est lié
    public function index(Request $request)
    {
        $professeur = $request->user();

        // Matières enseignées par le professeur
        $matieresIds = $professeur->matieresProfesseur->pluck('id');

        // Sujets liés aux forums de ses matières
        $sujets = SujetsForum::whereHas('forum', function($q) use ($matieresIds) {
            $q->whereIn('matiere_id', $matieresIds);
        })->with('auteur')->get();

        return response()->json($sujets);
    }

    // Répondre à un sujet
    public function repondre(Request $request, $sujet_id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $sujet = SujetsForum::findOrFail($sujet_id);

        // Vérifier que le sujet est dans une matière du prof
        if (!$request->user()->matieresProfesseur->contains($sujet->forum->matiere_id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $message = MessagesForum::create([
            'sujet_id' => $sujet->id,
            'user_id' => $request->user()->id,
            'message' => $request->message
        ]);

        return response()->json($message);
    }
}
