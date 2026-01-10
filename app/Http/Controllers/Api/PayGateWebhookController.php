<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayGateWebhookController extends Controller
{
    /**
     * Webhook pour recevoir les notifications de PayGate
     * POST /api/webhooks/paygate
     */
    public function handle(Request $request)
    {
        try {
            // Valider la signature PayGate (important pour la sécurité)
            // $this->validateSignature($request);

            $transactionId = $request->input('transaction_id');
            $status = $request->input('status'); // 'completed', 'failed', etc.
            $txReference = $request->input('tx_reference');

            // Trouver le paiement
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if (!$payment) {
                Log::warning('Payment not found for webhook', [
                    'transaction_id' => $transactionId
                ]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Mettre à jour le statut
            if ($status === 'completed' || $status === 'success') {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'tx_reference' => $txReference ?? $payment->tx_reference
                ]);

                // Mettre à jour le statut de l'utilisateur
                if ($payment->user) {
                    $payment->user->update([
                        'has_paid' => true,
                        'payment_date' => now()
                    ]);
                }

                Log::info('Payment confirmed via webhook', [
                    'transaction_id' => $transactionId,
                    'user_id' => $payment->user_id
                ]);
            } elseif ($status === 'failed' || $status === 'cancelled') {
                $payment->update([
                    'status' => 'failed'
                ]);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Webhook error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Valider la signature PayGate pour sécuriser le webhook
     */
    private function validateSignature(Request $request)
    {
        // Implémenter selon la documentation PayGate
        // Généralement, PayGate envoie une signature HMAC
    }
}
