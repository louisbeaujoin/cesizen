{{-- Formulaire de modification d'une page d'information --}}
@extends('layouts.admin')

@section('title', 'Modifier une page — Administration')

@section('content')
<div class="container container-small">
    <h1>Modifier : {{ $page->title }}</h1>

    <form method="POST" action="{{ route('admin.information.update', $page) }}">
        @csrf
        {{-- Simule une requête PUT --}}
        @method('PUT')

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="12" required>{{ old('content', $page->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="sort_order">Ordre d'affichage</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}">
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                Publiée
            </label>
        </div>

        <button type="submit" class="btn">Mettre à jour</button>
        <a href="{{ route('admin.information.index') }}">Annuler</a>
    </form>
</div>
@endsection
