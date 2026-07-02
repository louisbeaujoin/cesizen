<?php

use App\Models\BreathingExercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// BRE-F-001 — Affichage de la liste des exercices
test('breathing index shows active exercises', function () {
    BreathingExercise::create(['name' => 'Méthode 7-4-8', 'inspiration_duration' => 7, 'apnea_duration' => 4, 'expiration_duration' => 8, 'is_active' => true]);
    BreathingExercise::create(['name' => 'Méthode 5-5', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5, 'is_active' => true]);
    BreathingExercise::create(['name' => 'Inactive', 'inspiration_duration' => 3, 'apnea_duration' => 0, 'expiration_duration' => 3, 'is_active' => false]);

    $response = $this->get('/respiration');
    $response->assertStatus(200);
    $response->assertSee('Méthode 7-4-8');
    $response->assertSee('Méthode 5-5');
    $response->assertDontSee('Inactive');
});

// BRE-F-002 — Lancement exercice 7-4-8
test('can launch predefined exercise 748', function () {
    $exercise = BreathingExercise::create(['name' => 'Méthode 7-4-8', 'inspiration_duration' => 7, 'apnea_duration' => 4, 'expiration_duration' => 8, 'is_active' => true]);

    $response = $this->get("/respiration/exercice/{$exercise->id}");
    $response->assertStatus(200);
    $response->assertSee('Méthode 7-4-8');
    $response->assertSee('7'); // inspiration
    $response->assertSee('4'); // apnea
    $response->assertSee('8'); // expiration
});

// BRE-F-003 — Lancement exercice 5-5
test('can launch predefined exercise 55', function () {
    $exercise = BreathingExercise::create(['name' => 'Méthode 5-5', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5, 'is_active' => true]);

    $response = $this->get("/respiration/exercice/{$exercise->id}");
    $response->assertStatus(200);
    $response->assertSee('Méthode 5-5');
});

// BRE-F-004 — Lancement exercice 4-6
test('can launch predefined exercise 46', function () {
    $exercise = BreathingExercise::create(['name' => 'Méthode 4-6', 'inspiration_duration' => 4, 'apnea_duration' => 0, 'expiration_duration' => 6, 'is_active' => true]);

    $response = $this->get("/respiration/exercice/{$exercise->id}");
    $response->assertStatus(200);
    $response->assertSee('Méthode 4-6');
});

// BRE-F-005 — Exercice personnalisé
test('can launch custom exercise with parameters', function () {
    $response = $this->get('/respiration/exercice?inspiration=6&apnea=3&expiration=9&cycles=4');
    $response->assertStatus(200);
    $response->assertSee('personnalis');
    $response->assertSee('6');
    $response->assertSee('3');
    $response->assertSee('9');
});

// BRE-F-006 — Sauvegarde session (connecté)
test('authenticated user can save a breathing session', function () {
    $user = User::factory()->create();
    $exercise = BreathingExercise::create(['name' => 'Test', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5, 'is_active' => true]);

    $response = $this->actingAs($user)->postJson('/respiration/session', [
        'breathing_exercise_id' => $exercise->id,
        'inspiration_duration' => 5,
        'apnea_duration' => 0,
        'expiration_duration' => 5,
        'total_cycles' => 6,
        'duration_seconds' => 60,
    ]);

    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('breathing_sessions', [
        'user_id' => $user->id,
        'breathing_exercise_id' => $exercise->id,
    ]);
});

// BRE-F-007 — Sauvegarde refusée (anonyme)
test('guest cannot save a breathing session', function () {
    $response = $this->postJson('/respiration/session', [
        'inspiration_duration' => 5,
        'apnea_duration' => 0,
        'expiration_duration' => 5,
        'total_cycles' => 6,
        'duration_seconds' => 60,
    ]);

    $response->assertStatus(401);
});

// BRE-F-008 — Validation des limites
test('session save validates duration limits', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/respiration/session', [
        'inspiration_duration' => 0,
        'apnea_duration' => 0,
        'expiration_duration' => 50,
        'total_cycles' => 6,
        'duration_seconds' => 60,
    ]);

    $response->assertStatus(422);
});

// BRE-F-009 — Admin : création d'un exercice
test('admin can create a breathing exercise', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post('/admin/respiration', [
        'name' => 'New Exercise',
        'description' => 'Test description',
        'inspiration_duration' => 3,
        'apnea_duration' => 3,
        'expiration_duration' => 3,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.breathing.index'));
    $this->assertDatabaseHas('breathing_exercises', ['name' => 'New Exercise']);
});

// BRE-F-010 — Admin : modification d'un exercice
test('admin can update a breathing exercise', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $exercise = BreathingExercise::create(['name' => 'Original', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5]);

    $response = $this->actingAs($admin)->put("/admin/respiration/{$exercise->id}", [
        'name' => 'Updated',
        'inspiration_duration' => 7,
        'apnea_duration' => 4,
        'expiration_duration' => 8,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.breathing.index'));
    expect($exercise->fresh()->name)->toBe('Updated');
    expect($exercise->fresh()->inspiration_duration)->toBe(7);
});

// BRE-F-011 — Admin : suppression d'un exercice
test('admin can delete a breathing exercise', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $exercise = BreathingExercise::create(['name' => 'To Delete', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5]);

    $response = $this->actingAs($admin)->delete("/admin/respiration/{$exercise->id}");

    $response->assertRedirect(route('admin.breathing.index'));
    $this->assertDatabaseMissing('breathing_exercises', ['id' => $exercise->id]);
});

// BRE-F-012 — Accessibilité publique
test('breathing exercises are accessible without login', function () {
    BreathingExercise::create(['name' => 'Public', 'inspiration_duration' => 5, 'apnea_duration' => 0, 'expiration_duration' => 5, 'is_active' => true]);

    $this->get('/respiration')->assertStatus(200);
    $this->get('/respiration/exercice?inspiration=5&apnea=0&expiration=5&cycles=6')->assertStatus(200);
});
