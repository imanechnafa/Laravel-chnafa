<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('departement_id')->constrained()->onDelete('restrict');
            $table->string('matricule')->unique();
            $table->date('date_embauche');
            $table->enum('role', ['employe', 'manager', 'admin'])->default('employe');
            $table->integer('solde_conge')->default(25);
            $table->timestamps();
            
            // Index
            $table->index('matricule');
            $table->index('role');
            $table->index('departement_id');
            $table->index(['departement_id', 'role']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('employes');
    }
};