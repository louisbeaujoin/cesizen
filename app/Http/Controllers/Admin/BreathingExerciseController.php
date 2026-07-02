<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreathingExercise;
use Illuminate\Http\Request;

// Gère le CRUD des exercices de respiration dans l'espace admin
class BreathingExerciseController extends Controller
{
    // Liste tous les exercices, du plus récent au plus ancien
    public function index()
    {
        $exercises = BreathingExercise::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.breathing.index', compact('exercises'));
    }

    // Affiche le formulaire de création d'un exercice
    public function create()
    {
        return view('admin.breathing.create');
    }

    // Enregistre un nouvel exercice en base
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspiration_duration' => 'required|integer|min:1|max:30',
            'apnea_duration' => 'required|integer|min:0|max:30',
            'expiration_duration' => 'required|integer|min:1|max:30',
            'is_active' => 'boolean',
        ]);

        // La case à cocher envoie 1 ou rien, on force un booléen
        $validated['is_active'] = $request->boolean('is_active');

        BreathingExercise::create($validated);

        return redirect()->route('admin.breathing.index')->with('success', 'Exercice créé.');
    }

    // Affiche le formulaire de modification d'un exercice
    public function edit(BreathingExercise $exercise)
    {
        return view('admin.breathing.edit', compact('exercise'));
    }

    // Met à jour un exercice existant
    public function update(Request $request, BreathingExercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspiration_duration' => 'required|integer|min:1|max:30',
            'apnea_duration' => 'required|integer|min:0|max:30',
            'expiration_duration' => 'required|integer|min:1|max:30',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $exercise->update($validated);

        return redirect()->route('admin.breathing.index')->with('success', 'Exercice mis à jour.');
    }

    // Supprime un exercice
    public function destroy(BreathingExercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('admin.breathing.index')->with('success', 'Exercice supprimé.');
    }
}
