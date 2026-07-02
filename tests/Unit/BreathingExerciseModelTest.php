<?php

use App\Models\BreathingExercise;
use App\Models\BreathingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// BRE-U-001 — Calcul de la durée d'un cycle
test('getCycleDuration returns sum of all durations', function () {
    $exercise = BreathingExercise::create([
        'name' => 'Test 7-4-8',
        'inspiration_duration' => 7,
        'apnea_duration' => 4,
        'expiration_duration' => 8,
    ]);
    expect($exercise->getCycleDuration())->toBe(19);
});

test('getCycleDuration works with zero apnea', function () {
    $exercise = BreathingExercise::create([
        'name' => 'Test 5-5',
        'inspiration_duration' => 5,
        'apnea_duration' => 0,
        'expiration_duration' => 5,
    ]);
    expect($exercise->getCycleDuration())->toBe(10);
});

// BRE-U-002 — Scope active
test('active scope returns only active exercises', function () {
    BreathingExercise::create(['name' => 'Active 1', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5, 'is_active' => true]);
    BreathingExercise::create(['name' => 'Active 2', 'inspiration_duration' => 4, 'apnea_duration' => 0, 'expiration_duration' => 6, 'is_active' => true]);
    BreathingExercise::create(['name' => 'Inactive', 'inspiration_duration' => 7, 'apnea_duration' => 4, 'expiration_duration' => 8, 'is_active' => false]);

    $active = BreathingExercise::active()->get();
    expect($active)->toHaveCount(2);
});

// BRE-U-003 — Relation BreathingSession → User
test('breathing session belongs to user', function () {
    $user = User::factory()->create();
    $session = BreathingSession::create([
        'user_id' => $user->id,
        'inspiration_duration' => 5,
        'apnea_duration' => 0,
        'expiration_duration' => 5,
        'total_cycles' => 6,
        'duration_seconds' => 60,
    ]);

    expect($session->user->id)->toBe($user->id);
});

// BRE-U-004 — Relation nullable Session → Exercise
test('breathing session can have null exercise', function () {
    $user = User::factory()->create();
    $session = BreathingSession::create([
        'user_id' => $user->id,
        'breathing_exercise_id' => null,
        'inspiration_duration' => 6,
        'apnea_duration' => 3,
        'expiration_duration' => 9,
        'total_cycles' => 4,
        'duration_seconds' => 72,
    ]);

    expect($session->breathingExercise)->toBeNull();
});
