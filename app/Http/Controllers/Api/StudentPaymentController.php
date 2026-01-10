<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentPaymentController extends Controller
{
    /**
     * Enregistrer une transaction de paiement initiée
     * POST /api/student/validate-payment
     */
    public function validatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'tx_reference' => 'nullable|string|max:255',
            'method' => 'required|in:TMoney,Flooz',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user(); // Récupère l'utilisateur authentifié

            // Vérifier si cette transaction existe déjà
            $existingPayment = Payment::where('transaction_id', $request->transaction_id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction déjà enregistrée',
                    'payment' => $existingPayment
                ], 200);
            }

            // Créer le paiement avec statut "pending"
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $request->transaction_id,
                'tx_reference' => $request->tx_reference,
                'method' => $request->method,
                'amount' => $request->amount,
                'status' => 'pending',
            ]);

            // Log pour débogage
            Log::info('Payment initiated', [
                'user_id' => $user->id,
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount
            ]);

            // IMPORTANT: Ici, vous devriez vérifier le statut du paiement avec PayGate
            // Pour l'instant, on marque comme complété automatiquement
            // En production, utilisez l'API PayGate pour vérifier la transaction
            $this->verifyPaymentWithPayGate($payment);

            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès',
                'payment' => $payment
            ], 201);

        } catch (\Exception $e) {
            Log::error('Payment validation error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier si l'élève a payé
     * GET /api/student/check-access
     */
    public function checkAccess(Request $request)
    {
        try {
            $user = $request->user();

            // Vérifier si l'utilisateur a un paiement complété
            $hasPaid = Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->exists();

            // Ou utiliser la colonne has_paid si vous l'avez ajoutée
            // $hasPaid = $user->has_paid ?? false;

            return response()->json([
                'success' => true,
                'has_paid' => $hasPaid,
                'message' => $hasPaid 
                    ? 'Accès autorisé' 
                    : 'Paiement requis pour accéder à cette fonctionnalité'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Check access error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'has_paid' => false,
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }

    /**
     * Obtenir le total payé par l'élève
     * GET /api/student/total-paid
     */
    public function getTotalPaid(Request $request)
    {
        try {
            $user = $request->user();

            // Calculer le total des paiements complétés
            $totalPaid = Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount');

            return response()->json([
                'success' => true,
                'total_paid' => (int) $totalPaid,
                'currency' => 'FCFA'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Get total paid error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'total_paid' => 0,
                'message' => 'Erreur lors de la récupération du total'
            ], 500);
        }
    }

    /**
     * Vérifier le statut du paiement avec PayGate
     * À implémenter selon la documentation PayGate
     */
    private function verifyPaymentWithPayGate(Payment $payment)
    {
        // TODO: Implémenter la vérification via l'API PayGate
        // Exemple de logique :
        
        // 1. Appeler l'API PayGate pour vérifier la transaction
        // 2. Si le paiement est confirmé, mettre à jour le statut
        // 3. Mettre à jour has_paid dans la table users si nécessaire
        
        // Pour l'instant, on simule une vérification réussie après 5 secondes
        // En production, utilisez un job en queue ou une webhook PayGate
        
        // Exemple avec un job:
        // dispatch(new VerifyPayGatePaymentJob($payment));
        
        // Ou marquer directement comme complété (DÉVELOPPEMENT SEULEMENT)
        // $payment->update([
        //     'status' => 'completed',
        //     'paid_at' => now()
        // ]);
        
        // if ($payment->user) {
        //     $payment->user->update([
        //         'has_paid' => true,
        //         'payment_date' => now()
        //     ]);
        // }
    }
}
