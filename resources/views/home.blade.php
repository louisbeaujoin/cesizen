{{-- Page d'accueil du site --}}
@extends('layouts.app')

@section('title', 'CESIZen — Accueil')

@section('content')
<div class="container">
    {{-- Bloc héros avec accroche principale --}}
    <section class="hero">
        <h1>CESIZen</h1>
        <p>Votre plateforme de santé mentale et gestion du stress</p>
        <p>Découvrez nos outils pour mieux comprendre et gérer votre stress au quotidien.</p>
    </section>

    {{-- Cartes de présentation des fonctionnalités --}}
    <section class="features">
        <div class="feature-card">
            <h2>Informations</h2>
            <p>Accédez à des contenus sur la santé mentale et la prévention du stress.</p>
            <a href="{{ route('information.index') }}" class="btn">Consulter</a>
        </div>

        <div class="feature-card">
            <h2>Exercices de respiration</h2>
            <p>Pratiquez la cohérence cardiaque avec des exercices guidés et personnalisables.</p>
            <a href="{{ route('breathing.index') }}" class="btn">Commencer</a>
        </div>

        {{-- Carte d'inscription visible uniquement pour les visiteurs non connectés --}}
        @guest
        <div class="feature-card">
            <h2>Créer un compte</h2>
            <p>Inscrivez-vous pour accéder à toutes les fonctionnalités et suivre votre progression.</p>
            <a href="{{ route('register') }}" class="btn">S'inscrire</a>
        </div>
        @endguest
    </section>

    {{-- Aperçu des 3 premières pages d'information publiées --}}
    @if($pages->isNotEmpty())
    <section class="info-preview">
        <h2>Dernières informations</h2>
        <ul class="page-list">
            @foreach($pages->take(3) as $page)
                <li>
                    <a href="{{ route('information.show', $page->slug) }}">{{ $page->title }}</a>
                </li>
            @endforeach
        </ul>
    </section>
    @endif
</div>
@endsection
