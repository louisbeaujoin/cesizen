<?php

namespace App\Http\Controllers;

use App\Models\BreathingExercise;
use App\Models\BreathingSession;
use Illuminate\Http\Request;

// Gère l'affichage et la sauvegarde des exercices de respiration
class BreathingController extends Controller
{
    // Affiche la liste des exercices actifs
    public function index()
    {
        $exercises = BreathingExercise::active()->get();
        return view('breathing.index', compact('exercises'));
    }

    // Affiche la page d'un exercice (prédéfini ou personnalisé)
    public function exercise(Request $request, ?BreathingExercise $exercise = null)
    {
        // Bloque l'accès aux exercices désactivés
        if ($exercise && !$exercise->is_active) {
            abort(404);
        }

        // Cast en entier pour éviter toute injection via les paramètres URL dans le JS
        $inspiration = (int) $request->input('inspiration', $exercise?->inspiration_duration ?? 5);
        $apnea       = (int) $request->input('apnea', $exercise?->apnea_duration ?? 0);
        $expiration  = (int) $request->input('expiration', $exercise?->expiration_duration ?? 5);
        $cycles      = (int) $request->input('cycles', 6);

        return view('breathing.exercise', compact('exercise', 'inspiration', 'apnea', 'expiration', 'cycles'));
    }

    // Enregistre une session de respiration terminée
    public function saveSession(Request $request)
    {
        $validated = $request->validate([
            'breathing_exercise_id' => 'nullable|exists:breathing_exercises,id',
            'inspiration_duration' => 'required|integer|min:1|max:30',
            'apnea_duration' => 'required|integer|min:0|max:30',
            'expiration_duration' => 'required|integer|min:1|max:30',
            'total_cycles' => 'required|integer|min:1',
            'duration_seconds' => 'required|integer|min:1',
        ]);

        // Associe la session à l'utilisateur connecté
        $validated['user_id'] = auth()->id();

        BreathingSession::create($validated);

        return response()->json(['success' => true]);
    }
}
