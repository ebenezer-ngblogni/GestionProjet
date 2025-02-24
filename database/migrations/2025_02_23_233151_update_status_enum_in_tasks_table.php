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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');

        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['en_attente', 'en_cours', 'termine'])->default('en_attente');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Suppression de la colonne mise à jour
            $table->dropColumn('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            // Réajout de l'ancienne colonne ENUM avec les anciennes valeurs
            $table->enum('status', ['en cours', 'terminé', 'en attente'])->default('en attente');
        });
    }
};
