<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Enregistre une session de respiration effectuée par un utilisateur
class BreathingSession extends Model
{
    use HasFactory;

    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'user_id',
        'breathing_exercise_id',
        'inspiration_duration',
        'apnea_duration',
        'expiration_duration',
        'total_cycles',
        'duration_seconds',
    ];

    // Une session appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une session est liée à un exercice (peut être null pour les sessions personnalisées)
    public function breathingExercise()
    {
        return $this->belongsTo(BreathingExercise::class);
    }
}
