{{-- Liste admin des exercices de respiration avec pagination --}}
@extends('layouts.admin')

@section('title', 'Exercices de respiration — Administration')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Exercices de respiration</h1>
        <a href="{{ route('admin.breathing.create') }}" class="btn">Créer un exercice</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Inspiration</th>
                <th>Apnée</th>
                <th>Expiration</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exercises as $exercise)
                <tr>
                    <td>{{ $exercise->id }}</td>
                    <td>{{ $exercise->name }}</td>
                    <td>{{ $exercise->inspiration_duration }}s</td>
                    <td>{{ $exercise->apnea_duration }}s</td>
                    <td>{{ $exercise->expiration_duration }}s</td>
                    <td>{{ $exercise->is_active ? 'Oui' : 'Non' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.breathing.edit', $exercise) }}">Modifier</a>
                        {{-- Confirmation avant suppression pour éviter les suppressions accidentelles --}}
                        <form action="{{ route('admin.breathing.destroy', $exercise) }}" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cet exercice ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Liens de pagination générés par Laravel --}}
    {{ $exercises->links() }}
</div>
@endsection
