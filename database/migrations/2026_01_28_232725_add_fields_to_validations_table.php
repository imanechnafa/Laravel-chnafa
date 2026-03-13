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
        Schema::table('validations', function (Blueprint $table) {
            // Ajout des colonnes nécessaires
            $table->unsignedBigInteger('conge_id')->after('id');
            $table->unsignedBigInteger('validated_by_user_id')->after('conge_id');
            $table->string('statut')->default('en_attente')->after('validated_by_user_id');
            $table->text('commentaire')->nullable()->after('statut');

            // Définition des clés étrangères
            $table->foreign('conge_id')
                  ->references('id')
                  ->on('conges')
                  ->onDelete('cascade');

            $table->foreign('validated_by_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validations', function (Blueprint $table) {
            // Supprimer les clés étrangères
            $table->dropForeign(['conge_id']);
            $table->dropForeign(['validated_by_user_id']);

            // Supprimer les colonnes
            $table->dropColumn(['conge_id', 'validated_by_user_id', 'statut', 'commentaire']);
        });
    }
};
