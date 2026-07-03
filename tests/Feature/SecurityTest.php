<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('security headers are present on home page', function () {
    $response = $this->get('/');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
});

test('throttle middleware blocks after 5 login attempts', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post('/connexion', ['email' => 'brute@test.com', 'password' => 'wrong']);
    }
    $response = $this->post('/connexion', ['email' => 'brute@test.com', 'password' => 'wrong']);
    $response->assertStatus(429);
});

test('xss cast int protection on breathing page', function () {
    // Injection XSS via parametre inspiration URL
    $response = $this->get('/respiration?inspiration=5%3Balert(1)%2F%2F');
    $response->assertStatus(200);
    $response->assertDontSee('alert(1)', false);
});
