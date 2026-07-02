<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crée la table des exercices de respiration
return new class extends Migration
{
    // Crée la table
    public function up(): void
    {
        Schema::create('breathing_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('inspiration_duration'); // Durée en secondes
            $table->integer('apnea_duration');        // Durée en secondes (0 = pas d'apnée)
            $table->integer('expiration_duration');   // Durée en secondes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    // Supprime la table
    public function down(): void
    {
        Schema::dropIfExists('breathing_exercises');
    }
};
