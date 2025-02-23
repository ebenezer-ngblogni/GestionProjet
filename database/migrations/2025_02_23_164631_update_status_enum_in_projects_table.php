<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Suppression de l'ancienne colonne ENUM
            $table->dropColumn('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            // Ajout de la nouvelle colonne ENUM avec les valeurs correctes
            $table->enum('status', ['en_attente', 'en_cours', 'termine'])->default('en_attente');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Suppression de la colonne mise à jour
            $table->dropColumn('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            // Réajout de l'ancienne colonne ENUM avec les anciennes valeurs
            $table->enum('status', ['en cours', 'terminé', 'en attente'])->default('en attente');
        });
    }
};
