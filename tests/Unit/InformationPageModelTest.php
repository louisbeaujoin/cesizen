<?php

use App\Models\InformationPage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// INF-U-001 — Scope published
test('published scope returns only published pages', function () {
    InformationPage::create(['title' => 'Published 1', 'slug' => 'pub-1', 'content' => 'c', 'is_published' => true]);
    InformationPage::create(['title' => 'Published 2', 'slug' => 'pub-2', 'content' => 'c', 'is_published' => true]);
    InformationPage::create(['title' => 'Draft', 'slug' => 'draft', 'content' => 'c', 'is_published' => false]);

    $published = InformationPage::published()->get();
    expect($published)->toHaveCount(2);
});

// INF-U-002 — Scope ordered
test('ordered scope sorts by sort_order ascending', function () {
    InformationPage::create(['title' => 'Third', 'slug' => 'third', 'content' => 'c', 'sort_order' => 3]);
    InformationPage::create(['title' => 'First', 'slug' => 'first', 'content' => 'c', 'sort_order' => 1]);
    InformationPage::create(['title' => 'Second', 'slug' => 'second', 'content' => 'c', 'sort_order' => 2]);

    $ordered = InformationPage::ordered()->get();
    expect($ordered[0]->title)->toBe('First');
    expect($ordered[1]->title)->toBe('Second');
    expect($ordered[2]->title)->toBe('Third');
});
