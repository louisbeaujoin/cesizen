{{-- Tableau de bord admin : statistiques globales de l'application --}}
@extends('layouts.admin')

@section('title', 'Tableau de bord — Administration')

@section('content')
<div class="container">
    <h1>Tableau de bord</h1>

    {{-- Grille de statistiques : chaque carte affiche un compteur et un lien de gestion --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $stats['users'] }}</h3>
            <p>Utilisateurs</p>
            <a href="{{ route('admin.users.index') }}">Gérer</a>
        </div>
        <div class="stat-card">
            <h3>{{ $stats['pages'] }}</h3>
            <p>Pages d'information</p>
            <a href="{{ route('admin.information.index') }}">Gérer</a>
        </div>
        <div class="stat-card">
            <h3>{{ $stats['exercises'] }}</h3>
            <p>Exercices de respiration</p>
            <a href="{{ route('admin.breathing.index') }}">Gérer</a>
        </div>
        {{-- Les sessions sont en lecture seule : pas de lien de gestion --}}
        <div class="stat-card">
            <h3>{{ $stats['sessions'] }}</h3>
            <p>Sessions de respiration</p>
        </div>
    </div>
</div>
@endsection
