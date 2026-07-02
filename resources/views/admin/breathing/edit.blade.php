{{-- Formulaire de modification d'un exercice de respiration --}}
@extends('layouts.admin')

@section('title', 'Modifier un exercice — Administration')

@section('content')
<div class="container container-small">
    <h1>Modifier : {{ $exercise->name }}</h1>

    {{-- @method('PUT') simule la méthode HTTP PUT non supportée nativement par les formulaires HTML --}}
    <form method="POST" action="{{ route('admin.breathing.update', $exercise) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $exercise->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $exercise->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="inspiration_duration">Durée d'inspiration (secondes)</label>
            <input type="number" id="inspiration_duration" name="inspiration_duration" value="{{ old('inspiration_duration', $exercise->inspiration_duration) }}" min="1" max="30" required>
        </div>

        <div class="form-group">
            <label for="apnea_duration">Durée d'apnée (secondes)</label>
            <input type="number" id="apnea_duration" name="apnea_duration" value="{{ old('apnea_duration', $exercise->apnea_duration) }}" min="0" max="30" required>
        </div>

        <div class="form-group">
            <label for="expiration_duration">Durée d'expiration (secondes)</label>
            <input type="number" id="expiration_duration" name="expiration_duration" value="{{ old('expiration_duration', $exercise->expiration_duration) }}" min="1" max="30" required>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exercise->is_active) ? 'checked' : '' }}>
                Actif
            </label>
        </div>

        <button type="submit" class="btn">Mettre à jour</button>
        <a href="{{ route('admin.breathing.index') }}">Annuler</a>
    </form>
</div>
@endsection
