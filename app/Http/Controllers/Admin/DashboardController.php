<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InformationPage;
use App\Models\BreathingExercise;
use App\Models\BreathingSession;

// Affiche le tableau de bord admin avec les statistiques globales
class DashboardController extends Controller
{
    // Compte les enregistrements de chaque entité et les passe à la vue
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'pages' => InformationPage::count(),
            'exercises' => BreathingExercise::count(),
            'sessions' => BreathingSession::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
