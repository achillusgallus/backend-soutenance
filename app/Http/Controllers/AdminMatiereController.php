<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;

class AdminMatiereController extends Controller
{
    public function index() { return Matiere::all(); }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'nullable',
            'classe' => 'required|string',
            'user_name' => 'required|string',  // nom envoyé dans la requête
        ]);

        // Cherche l'utilisateur avec ce nom ET rôle = 2
        $user = \App\Models\User::where('name', $request->user_name)->where('role_id', 2)->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable ou n\'a pas le rôle requis.'], 422);
        }
        
        $matiere = Matiere::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'classe' => $request->classe,
            'user_id' => $user->id,  // On met l'id trouvé en base
        ]);

        return response()->json($matiere);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|required',
            'description' => 'nullable',
            'classe' => 'required|string',
            'user_name' => 'nullable|string',
        ]);

        $data = $request->only(['nom', 'description', 'classe']);

        if ($request->filled('user_name')) {
            $user = \App\Models\User::where('name', $request->user_name)->where('role_id', 2)->first();
            if (!$user) {
                return response()->json(['message' => 'Utilisateur introuvable ou n\'a pas le rôle requis.'], 422);
            }
            $data['user_id'] = $user->id;
        }

        $matiere->update($data);
        return response()->json($matiere);
    }

    public function destroy($id)
    {
        Matiere::findOrFail($id)->delete();
        return response()->json(['message'=>'Matière supprimée']);
    }
}
