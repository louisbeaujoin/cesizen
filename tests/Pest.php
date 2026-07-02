<?php

use Tests\TestCase;

// Lie tous les tests Feature et Unit à la classe TestCase de Laravel
pest()->extend(TestCase::class)
    ->in('Feature', 'Unit');

// Extension personnalisée : vérifie qu'une valeur est égale à 1
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

// Fonctions utilitaires globales partagées entre les fichiers de tests
function something()
{
    // ...
}
