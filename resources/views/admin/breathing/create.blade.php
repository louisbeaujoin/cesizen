{{-- Formulaire de création d'un exercice de respiration --}}
@extends('layouts.admin')

@section('title', 'Créer un exercice — Administration')

@section('content')
<div class="container container-small">
    <h1>Créer un exercice de respiration</h1>

    <form method="POST" action="{{ route('admin.breathing.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="inspiration_duration">Durée d'inspiration (secondes)</label>
            <input type="number" id="inspiration_duration" name="inspiration_duration" value="{{ old('inspiration_duration', 5) }}" min="1" max="30" required>
        </div>

        <div class="form-group">
            <label for="apnea_duration">Durée d'apnée (secondes)</label>
            <input type="number" id="apnea_duration" name="apnea_duration" value="{{ old('apnea_duration', 0) }}" min="0" max="30" required>
        </div>

        <div class="form-group">
            <label for="expiration_duration">Durée d'expiration (secondes)</label>
            <input type="number" id="expiration_duration" name="expiration_duration" value="{{ old('expiration_duration', 5) }}" min="1" max="30" required>
        </div>

        {{-- Coché par défaut : les nouveaux exercices sont actifs --}}
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                Actif
            </label>
        </div>

        <button type="submit" class="btn">Créer</button>
        <a href="{{ route('admin.breathing.index') }}">Annuler</a>
    </form>
</div>
@endsection
