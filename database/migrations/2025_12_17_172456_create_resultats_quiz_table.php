<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resultats_quiz', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('eleve_id');

            $table->decimal('score', 5, 2);
            $table->timestamp('date_passage')->useCurrent();

            $table->foreign('quiz_id')
                  ->references('id')
                  ->on('quiz')
                  ->onDelete('cascade');

            $table->foreign('eleve_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats_quiz');
    }
};
