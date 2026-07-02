<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ajoute les colonnes role et is_active à la table users
return new class extends Migration
{
    // Ajoute les colonnes
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rôle de l'utilisateur : 'user' par défaut, 'admin' possible
            $table->string('role', 10)->default('user')->after('email');
            // Indique si le compte est actif
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    // Supprime les colonnes ajoutées
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
