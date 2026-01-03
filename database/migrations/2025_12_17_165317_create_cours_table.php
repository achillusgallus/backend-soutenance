<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professeur_id')->constrained('users')->cascadeOnDelete();
            $table->string('titre');
            $table->longText('contenu');
            $table->string('fichier')->nullable();
            $table->string('fichier_type')->nullable();
            $table->unsignedBigInteger('fichier_size')->nullable();
            $table->integer('duree')->nullable()->comment('durée en secondes pour les vidéos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
