<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_conge_id')->constrained()->onDelete('restrict');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jours');
            $table->text('motif');
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('commentaire_validation')->nullable();
            $table->timestamps();
            
            // Index pour les recherches fréquentes
            $table->index('statut');
            $table->index('date_debut');
            $table->index('date_fin');
            $table->index(['employe_id', 'statut']);
            $table->index(['type_conge_id', 'statut']);
            $table->index(['date_debut', 'date_fin']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conges');
    }
};