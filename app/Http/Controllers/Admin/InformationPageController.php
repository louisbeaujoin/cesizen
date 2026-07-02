<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Gère le CRUD des pages d'information dans l'espace admin
class InformationPageController extends Controller
{
    // Liste toutes les pages par ordre de tri
    public function index()
    {
        $pages = InformationPage::ordered()->paginate(20);
        return view('admin.information.index', compact('pages'));
    }

    // Affiche le formulaire de création d'une page
    public function create()
    {
        return view('admin.information.create');
    }

    // Enregistre une nouvelle page et génère son slug
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        // Génère le slug à partir du titre
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Ajoute un suffixe numérique si le slug existe déjà
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (InformationPage::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter++;
        }

        InformationPage::create($validated);

        return redirect()->route('admin.information.index')->with('success', 'Page créée.');
    }

    // Affiche le formulaire de modification d'une page
    public function edit(InformationPage $page)
    {
        return view('admin.information.edit', compact('page'));
    }

    // Met à jour une page existante et recalcule son slug
    public function update(Request $request, InformationPage $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Vérifie l'unicité du slug en excluant la page courante
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (InformationPage::where('slug', $validated['slug'])->where('id', '!=', $page->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter++;
        }

        $page->update($validated);

        return redirect()->route('admin.information.index')->with('success', 'Page mise à jour.');
    }

    // Supprime une page
    public function destroy(InformationPage $page)
    {
        $page->delete();

        return redirect()->route('admin.information.index')->with('success', 'Page supprimée.');
    }
}
