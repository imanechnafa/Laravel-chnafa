<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conge_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('employes')->onDelete('cascade');
            $table->enum('decision', ['approuve', 'rejete']);
            $table->text('commentaire')->nullable();
            $table->timestamp('date_validation')->useCurrent();
            $table->timestamps();
            
            // Index
            $table->index('conge_id');
            $table->index('manager_id');
            $table->index('decision');
            $table->index('date_validation');
        });
    }

    public function down()
    {
        Schema::dropIfExists('validations');
    }
};