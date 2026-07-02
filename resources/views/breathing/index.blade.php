{{-- Liste des exercices de respiration disponibles --}}
@extends('layouts.app')

@section('title', 'Exercices de respiration — CESIZen')

@section('content')
<div class="container">
    <h1>Exercices de respiration</h1>
    <p>La cohérence cardiaque est une technique de respiration qui aide à réduire le stress et l'anxiété.
       Elle s'articule autour de trois phases : inspiration, apnée (rétention), et expiration.</p>

    {{-- Exercices prédéfinis actifs récupérés depuis la base --}}
    <h2>Méthodes prédéfinies</h2>

    @if($exercises->isEmpty())
        <p>Aucun exercice disponible pour le moment.</p>
    @else
        <div class="exercise-grid">
            @foreach($exercises as $exercise)
                <div class="exercise-card">
                    <h3>{{ $exercise->name }}</h3>
                    {{-- Description optionnelle --}}
                    @if($exercise->description)
                        <p>{{ $exercise->description }}</p>
                    @endif
                    <div class="exercise-timing">
                        <span>Inspiration : {{ $exercise->inspiration_duration }}s</span>
                        <span>Apnée : {{ $exercise->apnea_duration }}s</span>
                        <span>Expiration : {{ $exercise->expiration_duration }}s</span>
                    </div>
                    {{-- Durée calculée via la méthode du modèle --}}
                    <p>Durée d'un cycle : {{ $exercise->getCycleDuration() }}s</p>
                    <a href="{{ route('breathing.exercise', $exercise) }}" class="btn">Lancer</a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Formulaire d'exercice personnalisé : envoie les durées via GET --}}
    <h2>Exercice personnalisé</h2>
    <p>Configurez vos propres durées pour l'exercice de cohérence cardiaque.</p>

    <form action="{{ route('breathing.exercise') }}" method="GET" class="custom-exercise-form">
        <div class="form-row">
            <div class="form-group">
                <label for="inspiration">Inspiration (secondes)</label>
                <input type="number" id="inspiration" name="inspiration" value="5" min="1" max="30" required>
            </div>
            <div class="form-group">
                <label for="apnea">Apnée (secondes)</label>
                <input type="number" id="apnea" name="apnea" value="0" min="0" max="30" required>
            </div>
            <div class="form-group">
                <label for="expiration">Expiration (secondes)</label>
                <input type="number" id="expiration" name="expiration" value="5" min="1" max="30" required>
            </div>
            <div class="form-group">
                <label for="cycles">Nombre de cycles</label>
                <input type="number" id="cycles" name="cycles" value="6" min="1" max="30" required>
            </div>
        </div>
        <button type="submit" class="btn">Lancer l'exercice personnalisé</button>
    </form>
</div>
@endsection
