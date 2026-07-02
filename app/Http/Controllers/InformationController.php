<?php

namespace App\Http\Controllers;

use App\Models\InformationPage;

// Gère l'affichage public des pages d'information
class InformationController extends Controller
{
    // Affiche la liste de toutes les pages publiées
    public function index()
    {
        $pages = InformationPage::published()->ordered()->get();
        return view('information.index', compact('pages'));
    }

    // Affiche une page d'information par son slug
    public function show(string $slug)
    {
        // Retourne 404 si la page n'existe pas ou n'est pas publiée
        $page = InformationPage::where('slug', $slug)->published()->firstOrFail();
        $pages = InformationPage::published()->ordered()->get();
        return view('information.show', compact('page', 'pages'));
    }
}
