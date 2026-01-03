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
        // Ajoute les colonnes seulement si elles n'existent pas déjà (évite les doublons)
        if (!Schema::hasColumn('cours', 'fichier')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->string('fichier')->nullable();
            });
        }

        if (!Schema::hasColumn('cours', 'fichier_type')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->string('fichier_type')->nullable();
            });
        }

        if (!Schema::hasColumn('cours', 'fichier_size')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->unsignedBigInteger('fichier_size')->nullable();
            });
        }

        if (!Schema::hasColumn('cours', 'duree')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->integer('duree')->nullable()->comment('durée en secondes pour les vidéos');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprime les colonnes seulement si elles existent pour éviter l'erreur SQL 1091
        $cols = [];
        if (Schema::hasColumn('cours', 'fichier')) {
            $cols[] = 'fichier';
        }
        if (Schema::hasColumn('cours', 'fichier_type')) {
            $cols[] = 'fichier_type';
        }
        if (Schema::hasColumn('cours', 'fichier_size')) {
            $cols[] = 'fichier_size';
        }
        if (Schema::hasColumn('cours', 'duree')) {
            $cols[] = 'duree';
        }

        if (!empty($cols)) {
            Schema::table('cours', function (Blueprint $table) use ($cols) {
                $table->dropColumn($cols);
            });
        }
    }
};
