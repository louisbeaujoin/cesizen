<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Représente un exercice de respiration prédéfini
class BreathingExercise extends Model
{
    use HasFactory;

    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'name',
        'description',
        'inspiration_duration',
        'apnea_duration',
        'expiration_duration',
        'is_active',
    ];

    // Casts automatiques des types
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Retourne la durée totale d'un cycle en secondes
    public function getCycleDuration(): int
    {
        return $this->inspiration_duration + $this->apnea_duration + $this->expiration_duration;
    }

    // Un exercice peut avoir plusieurs sessions
    public function sessions()
    {
        return $this->hasMany(BreathingSession::class);
    }

    // Filtre uniquement les exercices actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
