<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// USR-U-001 — Vérification du rôle administrateur
test('isAdmin returns true for admin role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    expect($admin->isAdmin())->toBeTrue();
});

test('isAdmin returns false for user role', function () {
    $user = User::factory()->create(['role' => 'user']);
    expect($user->isAdmin())->toBeFalse();
});

// USR-U-002 — Hashage du mot de passe
test('password is automatically hashed', function () {
    $user = User::factory()->create(['password' => 'monMotDePasse']);
    expect($user->password)->not->toBe('monMotDePasse');
    expect(Hash::check('monMotDePasse', $user->password))->toBeTrue();
});

// USR-U-003 — Attributs fillable
test('mass assignment respects fillable', function () {
    $user = User::create([
        'name' => 'Test',
        'email' => 'fillable@test.com',
        'password' => 'password',
        'role' => 'user',
        'remember_token' => 'should_be_ignored',
    ]);
    expect($user->remember_token)->toBeNull();
});
