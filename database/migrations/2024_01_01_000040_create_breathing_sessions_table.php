<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crée la table des sessions de respiration effectuées par les utilisateurs
return new class extends Migration
{
    // Crée la table
    public function up(): void
    {
        Schema::create('breathing_sessions', function (Blueprint $table) {
            $table->id();
            // L'utilisateur est nullable pour les sessions anonymes
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // L'exercice est nullable pour les sessions personnalisées
            $table->foreignId('breathing_exercise_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('inspiration_duration');
            $table->integer('apnea_duration');
            $table->integer('expiration_duration');
            $table->integer('total_cycles')->default(1);
            $table->integer('duration_seconds'); // Durée totale de la session
            $table->timestamps();
        });
    }

    // Supprime la table
    public function down(): void
    {
        Schema::dropIfExists('breathing_sessions');
    }
};
