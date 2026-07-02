<?php

namespace Database\Seeders;

use App\Models\BreathingExercise;
use Illuminate\Database\Seeder;

// Crée les exercices de respiration par défaut
class BreathingExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // Technique de relaxation profonde (7 secondes inspiration, 4 apnée, 8 expiration)
        BreathingExercise::create([
            'name' => 'Méthode 7-4-8',
            'description' => 'Technique de relaxation profonde. L\'inspiration longue suivie d\'une rétention et d\'une expiration encore plus longue favorise la détente et aide à trouver le sommeil.',
            'inspiration_duration' => 7,
            'apnea_duration' => 4,
            'expiration_duration' => 8,
            'is_active' => true,
        ]);

        // Cohérence cardiaque classique : 6 respirations par minute
        BreathingExercise::create([
            'name' => 'Méthode 5-5',
            'description' => 'La cohérence cardiaque classique. Un rythme régulier de 6 respirations par minute, idéal pour réduire le stress au quotidien.',
            'inspiration_duration' => 5,
            'apnea_duration' => 0,
            'expiration_duration' => 5,
            'is_active' => true,
        ]);

        // Variante apaisante avec expiration plus longue
        BreathingExercise::create([
            'name' => 'Méthode 4-6',
            'description' => 'Variante apaisante avec une expiration plus longue que l\'inspiration. Particulièrement efficace pour calmer l\'anxiété.',
            'inspiration_duration' => 4,
            'apnea_duration' => 0,
            'expiration_duration' => 6,
            'is_active' => true,
        ]);
    }
}
