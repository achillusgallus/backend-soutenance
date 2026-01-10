<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Identifiant PayGate
            $table->string('tx_reference')->nullable(); // Référence transaction PayGate
            $table->enum('method', ['TMoney', 'Flooz']); // Méthode de paiement
            $table->integer('amount'); // Montant en FCFA
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable(); // Date de confirmation du paiement
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();

            $table->index('user_id');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
