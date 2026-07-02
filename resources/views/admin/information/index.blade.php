{{-- Liste admin des pages d'information avec pagination --}}
@extends('layouts.admin')

@section('title', 'Pages d\'information — Administration')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Pages d'information</h1>
        <a href="{{ route('admin.information.create') }}" class="btn">Créer une page</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Slug</th>
                <th>Ordre</th>
                <th>Publiée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->sort_order }}</td>
                    <td>{{ $page->is_published ? 'Oui' : 'Non' }}</td>
                    <td class="actions">
                        {{-- Ouvre la page publique dans un nouvel onglet --}}
                        <a href="{{ route('information.show', $page->slug) }}" target="_blank">Voir</a>
                        <a href="{{ route('admin.information.edit', $page) }}">Modifier</a>
                        {{-- Confirmation avant suppression --}}
                        <form action="{{ route('admin.information.destroy', $page) }}" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cette page ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Liens de pagination --}}
    {{ $pages->links() }}
</div>
@endsection
