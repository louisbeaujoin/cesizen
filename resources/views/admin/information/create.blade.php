{{-- Formulaire de création d'une page d'information --}}
@extends('layouts.admin')

@section('title', 'Créer une page — Administration')

@section('content')
<div class="container container-small">
    <h1>Créer une page d'information</h1>

    <form method="POST" action="{{ route('admin.information.store') }}">
        @csrf

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="12" required>{{ old('content') }}</textarea>
        </div>

        <div class="form-group">
            <label for="sort_order">Ordre d'affichage</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
        </div>

        {{-- Cochée par défaut : les nouvelles pages sont publiées immédiatement --}}
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                Publiée
            </label>
        </div>

        <button type="submit" class="btn">Créer</button>
        <a href="{{ route('admin.information.index') }}">Annuler</a>
    </form>
</div>
@endsection
