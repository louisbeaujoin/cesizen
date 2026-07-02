<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Représente une page d'information sur la santé mentale
class InformationPage extends Model
{
    use HasFactory;

    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'title',
        'slug',
        'content',
        'sort_order',
        'is_published',
    ];

    // Casts automatiques des types
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    // Filtre uniquement les pages publiées
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Trie les pages par ordre de tri croissant
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
