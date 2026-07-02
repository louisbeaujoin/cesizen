<?php

use App\Models\InformationPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// INF-F-001 — Affichage de la liste des pages
test('information index shows published pages', function () {
    InformationPage::create(['title' => 'Published', 'slug' => 'published', 'content' => 'Content', 'is_published' => true]);
    InformationPage::create(['title' => 'Draft', 'slug' => 'draft', 'content' => 'Content', 'is_published' => false]);

    $response = $this->get('/informations');
    $response->assertStatus(200);
    $response->assertSee('Published');
    $response->assertDontSee('Draft');
});

// INF-F-002 — Consultation d'une page
test('can view a published information page', function () {
    InformationPage::create(['title' => 'Santé mentale', 'slug' => 'sante-mentale', 'content' => 'Contenu important', 'is_published' => true]);

    $response = $this->get('/informations/sante-mentale');
    $response->assertStatus(200);
    $response->assertSee('Santé mentale');
    $response->assertSee('Contenu important');
});

// INF-F-003 — Page inexistante
test('non-existent slug returns 404', function () {
    $response = $this->get('/informations/page-inexistante');
    $response->assertStatus(404);
});

// INF-F-004 — Admin : création d'une page
test('admin can create an information page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/admin/informations/creer');
    $response->assertStatus(200);

    $response = $this->actingAs($admin)->post('/admin/informations', [
        'title' => 'Nouvelle Page',
        'content' => 'Contenu de la nouvelle page',
        'sort_order' => 5,
        'is_published' => true,
    ]);

    $response->assertRedirect(route('admin.information.index'));
    $this->assertDatabaseHas('information_pages', [
        'title' => 'Nouvelle Page',
        'slug' => 'nouvelle-page',
    ]);
});

// INF-F-005 — Admin : modification d'une page
test('admin can update an information page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $page = InformationPage::create(['title' => 'Original', 'slug' => 'original', 'content' => 'Content']);

    $response = $this->actingAs($admin)->put("/admin/informations/{$page->id}", [
        'title' => 'Modified',
        'content' => 'Updated content',
        'sort_order' => 1,
        'is_published' => true,
    ]);

    $response->assertRedirect(route('admin.information.index'));
    expect($page->fresh()->title)->toBe('Modified');
});

// INF-F-006 — Admin : suppression d'une page
test('admin can delete an information page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $page = InformationPage::create(['title' => 'To Delete', 'slug' => 'to-delete', 'content' => 'Content']);

    $response = $this->actingAs($admin)->delete("/admin/informations/{$page->id}");

    $response->assertRedirect(route('admin.information.index'));
    $this->assertDatabaseMissing('information_pages', ['id' => $page->id]);
});
