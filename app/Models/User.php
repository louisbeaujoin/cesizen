<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Représente un utilisateur de l'application
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'api_token',
    ];

    // Champs masqués dans les sérialisations JSON
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    // Casts automatiques des types
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Vérifie si l'utilisateur a le rôle administrateur
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Un utilisateur peut avoir plusieurs sessions de respiration
    public function breathingSessions()
    {
        return $this->hasMany(BreathingSession::class);
    }
}
