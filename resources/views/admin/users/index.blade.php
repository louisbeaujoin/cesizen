{{-- Liste admin des utilisateurs avec pagination --}}
@extends('layouts.admin')

@section('title', 'Utilisateurs — Administration')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="btn">Créer un utilisateur</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role === 'admin' ? 'Administrateur' : 'Utilisateur' }}</td>
                    <td>{{ $user->is_active ? 'Actif' : 'Désactivé' }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.users.edit', $user) }}">Modifier</a>
                        {{-- Les actions d'activation/suppression sont masquées pour l'admin connecté (auto-protection) --}}
                        @if($user->id !== auth()->id())
                            {{-- Bascule le statut actif/inactif du compte --}}
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit">{{ $user->is_active ? 'Désactiver' : 'Activer' }}</button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Liens de pagination --}}
    {{ $users->links() }}
</div>
@endsection
