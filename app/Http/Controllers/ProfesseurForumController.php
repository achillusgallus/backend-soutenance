<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SujetsForum;
use App\Models\MessagesForum;
use App\Models\Matiere;
use Illuminate\Support\Facades\Auth;

class ProfesseurForumController extends Controller
{
    // Nouvelle fonction pour lister les forums du prof
    public function getForums(Request $request)
    {
        // Récupérer les ID des matières du prof
        $matiereIds = $request->user()->matieres()->pluck('matieres.id');

        // Trouver les forums liés à ces matières
        $forums = \App\Models\Forum::whereIn('matiere_id', $matiereIds)
            ->with('matiere') // Charger les infos de la matière
            ->get();
            
        return response()->json($forums);
    }
    // Lister les sujets des forums auxquels le prof est lié
    public function index(Request $request)
    {
        $professeur = $request->user();

        // Matières enseignées par le professeur
        $matieresIds = $request->user()->matieres()->pluck('matieres.id');

        // Sujets liés aux forums de ses matières
        $sujets = SujetsForum::whereHas('forum', function($query) use ($matieresIds) {
            $query->whereIn('matiere_id', $matieresIds);
        })->with(['auteur', 'forum.matiere'])
        ->withCount('messages')
        ->latest()
        ->get();

        $formatted = $sujets->map(function ($sujet) {
            return [
                'id' => $sujet->id,
                'titre' => $sujet->titre,
                'contenu' => $sujet->contenu, 
                'created_at_human' => $sujet->created_at->diffForHumans(),
                'auteur' => $sujet->auteur, // App uses topic['auteur']['name']
                'matiere_nom' => $sujet->forum->matiere->nom ?? 'Général',
                'messages_count' => $sujet->messages_count,
            ];
        });
        return response()->json($formatted);
    }

    // Répondre à un sujet
        public function repondre(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        MessagesForum::create([
            'sujet_id' => $id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);
        return response()->json(['message' => 'Réponse envoyée']);
    }
}