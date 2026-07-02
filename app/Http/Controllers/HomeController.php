<?php

namespace App\Http\Controllers;

use App\Models\InformationPage;

// Gère la page d'accueil du site
class HomeController extends Controller
{
    // Affiche les pages d'information publiées sur la page d'accueil
    public function index()
    {
        $pages = InformationPage::published()->ordered()->get();
        return view('home', compact('pages'));
    }
}
