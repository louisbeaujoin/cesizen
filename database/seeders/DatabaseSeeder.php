<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Seeder principal qui appelle tous les seeders dans l'ordre
class DatabaseSeeder extends Seeder
{
    // Lance les seeders dans l'ordre des dépendances
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            InformationPageSeeder::class,
            BreathingExerciseSeeder::class,
        ]);
    }
}
