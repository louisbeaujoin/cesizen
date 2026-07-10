<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'category', 'message', 'status', 'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categoryLabel(): string
    {
        return match($this->category) {
            'bug'        => 'Bug / Erreur',
            'suggestion' => 'Suggestion',
            'question'   => 'Question',
            default      => 'Autre',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'ouvert'   => 'Ouvert',
            'en_cours' => 'En cours',
            'resolu'   => 'Résolu',
            'ferme'    => 'Fermé',
            default    => 'Ouvert',
        };
    }
}
