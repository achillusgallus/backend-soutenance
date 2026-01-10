<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Payment;

class RequirePayment
{
    /**
     * Vérifier si l'utilisateur a payé avant d'accéder aux forums
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

        // Vérifier si l'utilisateur a un paiement complété
        $hasPaid = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasPaid) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement requis pour accéder aux forums',
                'has_paid' => false
            ], 403);
        }

        return $next($request);
    }
}
