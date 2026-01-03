<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Cours;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CoursController extends Controller
{
    // Liste des cours pour l'élève
    public function index(Request $request)
    {
        $user = $request->user();
        
        // DEBUG: Voir les matières de l'élève
        $matieresIds = $user->matieresEleve->pluck('id')->toArray();
        \Log::info('DEBUG Matières élève', [
            'user_id' => $user->id,
            'user_classe' => $user->classe,
            'matieres_ids' => $matieresIds
        ]);
        
        // Si l'élève n'a aucune matière, retourner tous les cours de sa classe
        if (empty($matieresIds)) {
            \Log::warning('Élève sans matières assignées, utilisation de la classe');
            // Récupérer les matières par classe au lieu de la relation
            $matieres = \App\Models\Matiere::where('classe', $user->classe)->get();
            $matieresIds = $matieres->pluck('id')->toArray();
        }
        
        $matiereId = $request->query('matiere_id');
        
        if ($matiereId) {
            if (!in_array((int) $matiereId, $matieresIds, true)) {
                return response()->json([
                    'message' => 'Accès refusé à cette matière',
                    'debug' => [
                        'matiere_demandee' => $matiereId,
                        'matieres_eleve' => $matieresIds
                    ]
                ], 403);
            }
            
            $cours = Cours::where('matiere_id', $matiereId)
                ->with('matiere', 'professeur')
                ->get();
        } else {
            $cours = Cours::whereIn('matiere_id', $matieresIds)
                ->with('matiere', 'professeur')
                ->get();
        }
        
        return response()->json($cours);
    }

    // Voir un cours spécifique
    public function show(Request $request, $id)
    {
        $cours = Cours::with('matiere', 'professeur')->findOrFail($id);

        // Vérifier que l'élève a accès
        if (!$request->user()->matieresEleve->contains($cours->matiere_id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        // Si le cours a un fichier et que le client demande le stream (Accept header video/* or pdf or ?stream=1)
        $accept = $request->header('Accept', '');
        $wantStream = $request->query('stream') == '1' || str_starts_with($accept, 'video/') || str_contains($accept, 'application/pdf');

        if ($cours->fichier && $wantStream) {
            $full = storage_path('app/public/' . $cours->fichier);
            if (!file_exists($full)) {
                return response()->json(['message' => 'Fichier introuvable'], 404);
            }

            $mime = $cours->fichier_type ?? mime_content_type($full);
            return $this->streamFileResponse($full, $mime, $cours->titre);
        }

        return response()->json($cours);
    }

    private function streamFileResponse(string $filePath, string $mime, string $filename = null)
    {
        $size = filesize($filePath);
        $start = 0;
        $end = $size - 1;

        // Support pour l'en-tête Range
        $headers = [];
        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)) {
                $start = intval($matches[1]);
                if ($matches[2] !== '') {
                    $end = intval($matches[2]);
                }
            }
            $status = 206;
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        } else {
            $status = 200;
        }

        $length = $end - $start + 1;

        $response = new StreamedResponse(function () use ($filePath, $start, $end) {
            $fp = fopen($filePath, 'rb');
            fseek($fp, $start);
            $buffer = 1024 * 1024; // 1MB
            while (!feof($fp) && ($pos = ftell($fp)) <= $end) {
                $read = min($buffer, $end - $pos + 1);
                echo fread($fp, $read);
                flush();
            }
            fclose($fp);
        }, $status);

        $response->headers->set('Content-Type', $mime);
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Length', $length);
        if ($filename) {
            $disposition = 'inline';
            $response->headers->set('Content-Disposition', "{$disposition}; filename=\"{$filename}\"");
        }
        if (!empty($headers)) {
            foreach ($headers as $k => $v) {
                $response->headers->set($k, $v);
            }
        }

        return $response;
    }
}
