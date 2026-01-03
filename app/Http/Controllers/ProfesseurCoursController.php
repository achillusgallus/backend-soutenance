<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Cours;
use App\Models\Matiere;

class ProfesseurCoursController extends Controller
{
    // Lister les cours du professeur
    public function index(Request $request)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)
            ->with('matiere')
            ->get();

        return response()->json($cours);
    }

    // Créer un cours
    public function store(Request $request)
    {
        $request->validate([
            'matiere_nom' => 'required|string|max:255', // on valide le nom de la matière
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'fichier' => 'nullable|file|mimes:mp4,mov,avi,wmv,webm,pdf|max:51200', // max 50MB
        ]);

        // On récupère la matière par son nom
        $matiere = Matiere::where('nom', $request->matiere_nom)->first();

        if (!$matiere) {
            return response()->json([
                'error' => 'La matière spécifiée est introuvable.'
            ], 404);
        }

        $data = [
            'matiere_id' => $matiere->id,
            'professeur_id' => $request->user()->id,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
        ];

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $path = $file->store('uploads/cours', 'public');

            $data['fichier'] = $path;
            $data['fichier_type'] = $file->getClientMimeType();
            $data['fichier_size'] = $file->getSize();

            if (str_starts_with($data['fichier_type'], 'video/')) {
                $full = storage_path('app/public/' . $path);
                $duration = $this->getVideoDuration($full);
                $maxSeconds = 300; // exemple: 5 minutes
                if ($duration !== null && $duration > $maxSeconds) {
                    // supprimer le fichier si présent
                    Storage::disk('public')->delete($path);
                    return response()->json(['error' => "La vidéo dépasse la durée maximale de {$maxSeconds} secondes."], 422);
                }
                $data['duree'] = $duration;
            }
        }

        $cours = Cours::create($data);

        return response()->json($cours);
    }

    // Modifier un cours
    public function update(Request $request, $id)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'contenu' => 'sometimes|required|string',
            'fichier' => 'nullable|file|mimes:mp4,mov,avi,wmv,webm,pdf|max:51200',
        ]);

        $data = $request->only(['titre', 'contenu']);

        if ($request->hasFile('fichier')) {
            // supprimer ancien fichier
            if ($cours->fichier) {
                Storage::disk('public')->delete($cours->fichier);
            }

            $file = $request->file('fichier');
            $path = $file->store('uploads/cours', 'public');

            $data['fichier'] = $path;
            $data['fichier_type'] = $file->getClientMimeType();
            $data['fichier_size'] = $file->getSize();

            if (str_starts_with($data['fichier_type'], 'video/')) {
                $full = storage_path('app/public/' . $path);
                $duration = $this->getVideoDuration($full);
                $maxSeconds = 300; // 5 minutes par défaut
                if ($duration !== null && $duration > $maxSeconds) {
                    Storage::disk('public')->delete($path);
                    return response()->json(['error' => "La vidéo dépasse la durée maximale de {$maxSeconds} secondes."], 422);
                }
                $data['duree'] = $duration;
            }
        }

        $cours->update($data);

        return response()->json($cours);
    }

    // Supprimer un cours
    public function destroy(Request $request, $id)
    {
        $cours = Cours::where('professeur_id', $request->user()->id)->findOrFail($id);
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé']);
    }

    /**
     * Récupère la durée d'une vidéo en secondes via ffprobe si disponible.
     * Retourne null si ffprobe non disponible ou échec.
     */
    private function getVideoDuration(string $filePath): ?int
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $cmd = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($filePath);
        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0 || empty($output)) {
            return null;
        }

        $seconds = (int) round((float) $output[0]);
        return $seconds;
    }
}
