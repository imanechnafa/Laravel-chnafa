<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_conges', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('jours_annuels')->default(25);
            $table->boolean('est_paye')->default(true);
            $table->string('couleur')->nullable();
            $table->timestamps();
            
            // Index
            $table->index('nom');
            $table->index('est_paye');
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_conges');
    }
};