<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BreathingExercise;
use App\Models\BreathingSession;
use Illuminate\Http\Request;

class BreathingApiController extends Controller
{
    public function index()
    {
        $exercises = BreathingExercise::active()->get()->map(fn($e) => [
            'id' => $e->id,
            'name' => $e->name,
            'description' => $e->description,
            'inspiration_duration' => $e->inspiration_duration,
            'apnea_duration' => $e->apnea_duration,
            'expiration_duration' => $e->expiration_duration,
        ]);

        return response()->json($exercises);
    }

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

        $validated['user_id'] = auth()->id();

        BreathingSession::create($validated);

        return response()->json(['success' => true], 201);
    }
}
