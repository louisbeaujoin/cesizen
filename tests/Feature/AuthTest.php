<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// USR-F-001 — Inscription d'un nouveau compte
test('a visitor can register', function () {
    $response = $this->get('/inscription');
    $response->assertStatus(200);

    $response = $this->post('/inscription', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'user',
    ]);
});

// USR-F-002 — Inscription avec données invalides
test('registration fails with invalid data', function () {
    $response = $this->post('/inscription', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'different',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

// USR-F-003 — Inscription avec email déjà existant
test('registration fails with existing email', function () {
    User::factory()->create(['email' => 'existing@test.com']);

    $response = $this->post('/inscription', [
        'name' => 'Duplicate',
        'email' => 'existing@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});

// USR-F-004 — Connexion avec identifiants valides
test('a user can login with valid credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->get('/connexion');
    $response->assertStatus(200);

    $response = $this->post('/connexion', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

// USR-F-005 — Connexion avec identifiants invalides
test('login fails with wrong password', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->post('/connexion', [
        'email' => $user->email,
        'password' => 'wrong',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

// USR-F-006 — Connexion d'un compte désactivé
test('disabled account cannot login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
        'is_active' => false,
    ]);

    $response = $this->post('/connexion', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

// USR-F-007 — Déconnexion
test('a user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/deconnexion');

    $response->assertRedirect('/');
    $this->assertGuest();
});

// USR-F-008 — Modification du profil
test('a user can update their profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put('/profil', [
        'name' => 'Updated Name',
        'email' => 'updated@email.com',
    ]);

    $response->assertRedirect();
    expect($user->fresh()->name)->toBe('Updated Name');
    expect($user->fresh()->email)->toBe('updated@email.com');
});

// USR-F-009 — Changement de mot de passe
test('a user can change their password', function () {
    $user = User::factory()->create(['password' => bcrypt('oldpassword')]);

    $response = $this->actingAs($user)->put('/profil/mot-de-passe', [
        'current_password' => 'oldpassword',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();
    expect(Hash::check('newpassword123', $user->fresh()->password))->toBeTrue();
});

// USR-F-011 — Protection des routes admin
test('non-admin cannot access admin routes', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(403);
});

test('guest is redirected to login from admin', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/connexion');
});

// USR-F-012 — Protection auto-suppression admin
test('admin cannot deactivate themselves', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->patch("/admin/utilisateurs/{$admin->id}/toggle");
    $response->assertSessionHasErrors('error');
    expect($admin->fresh()->is_active)->toBeTrue();
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete("/admin/utilisateurs/{$admin->id}");
    $response->assertSessionHasErrors('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
