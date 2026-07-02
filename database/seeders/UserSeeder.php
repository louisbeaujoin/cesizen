<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Crée les comptes utilisateurs de base pour les tests et la démo
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@cesizen.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Compte utilisateur standard
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'user@cesizen.fr',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
