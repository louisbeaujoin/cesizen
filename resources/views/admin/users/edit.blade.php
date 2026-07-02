{{-- Formulaire de modification d'un compte utilisateur --}}
@extends('layouts.admin')

@section('title', 'Modifier un utilisateur — Administration')

@section('content')
<div class="container container-small">
    <h1>Modifier : {{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        {{-- Simule une requête PUT --}}
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
        </div>

        <button type="submit" class="btn">Mettre à jour</button>
        <a href="{{ route('admin.users.index') }}">Annuler</a>
    </form>
</div>
@endsection
