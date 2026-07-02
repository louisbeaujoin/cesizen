<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Crée la table des pages d'information
return new class extends Migration
{
    // Crée la table
    public function up(): void
    {
        Schema::create('information_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Identifiant URL de la page
            $table->text('content');
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    // Supprime la table
    public function down(): void
    {
        Schema::dropIfExists('information_pages');
    }
};
