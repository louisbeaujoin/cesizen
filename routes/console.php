<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Commande Artisan d'exemple qui affiche une citation inspirante
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Affiche une citation inspirante');
