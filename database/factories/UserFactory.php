<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Fabrique des utilisateurs fictifs pour les tests
class UserFactory extends Factory
{
    // Mot de passe partagé entre toutes les instances pour éviter de le recalculer à chaque fois
    protected static ?string $password;

    // Définit les valeurs par défaut d'un utilisateur généré
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    // Crée un utilisateur avec un email non vérifié
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
