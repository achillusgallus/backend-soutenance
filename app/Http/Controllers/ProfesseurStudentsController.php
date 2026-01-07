<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Matiere;

class ProfesseurStudentsController extends Controller
{
      /**
     * Récupère la liste des élèves qui sont dans les mêmes classes
     * que les matières enseignées par le professeur connecté.
     */
    public function getMyStudents(Request $request)
    {
        $teacher = $request->user();
        // 1. On récupère les noms des classes (tle_D, tle_A4, etc.) 
        // associées aux matières de ce professeur
        $classes = Matiere::where('user_id', $teacher->id)
                    ->pluck('classe')
                    ->unique()
                    ->toArray();
        // 2. On récupère les utilisateurs ayant le rôle "élève" (role_id = 3)
        // et qui appartiennent à ces classes
        $students = User::where('role_id', 3)
                    ->whereIn('classe', $classes)
                    ->orderBy('classe')
                    ->orderBy('name')
                    ->get(['id', 'name', 'surname', 'email', 'classe']);
        return response()->json($students);
    }
}
