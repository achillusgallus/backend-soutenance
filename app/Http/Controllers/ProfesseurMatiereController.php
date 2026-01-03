<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;
use App\Models\User;

class ProfesseurMatiereController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $matieres = Matiere::where('user_id', $user->id)->get();

        return response()->json($matieres);
    }
}
