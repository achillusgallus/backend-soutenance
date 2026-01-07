<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessagesForum;

class MessagesForumController extends Controller
{
    // Lister les messages d'un sujet
    public function index($sujet_id)
    {
        $messages = MessagesForum::where('sujet_id', $sujet_id)
            ->with('user')
            ->get();

        return response()->json($messages);
    }

    // Créer un message dans un sujet
    public function store(Request $request)
    {
        $request->validate([
            'sujet_id' => 'required|exists:sujets_forum,id',
            'message' => 'required|string',
        ]);

        $msg = MessagesForum::create([
            'sujet_id' => $request->sujet_id,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        return response()->json($msg);
    }
}
