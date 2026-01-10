<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerifyPayGatePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle()
    {
        try {
            // Appeler l'API PayGate pour vérifier la transaction
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paygate.api_key'),
                'Content-Type' => 'application/json'
            ])->get('https://paygate-api-url/verify', [
                'transaction_id' => $this->payment->transaction_id
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'completed') {
                    $this->payment->update([
                        'status' => 'completed',
                        'paid_at' => now()
                    ]);

                    if ($this->payment->user) {
                        $this->payment->user->update([
                            'has_paid' => true,
                            'payment_date' => now()
                        ]);
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'payment_id' => $this->payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
