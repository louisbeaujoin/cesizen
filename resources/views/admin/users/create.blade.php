{{-- Formulaire de création d'un compte utilisateur par l'admin --}}
@extends('layouts.admin')

@section('title', 'Créer un utilisateur — Administration')

@section('content')
<div class="container container-small">
    <h1>Créer un utilisateur</h1>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        {{-- Sélection du rôle : "user" par défaut, "admin" pour l'accès à l'espace administration --}}
        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
        </div>

        <button type="submit" class="btn">Créer</button>
        <a href="{{ route('admin.users.index') }}">Annuler</a>
    </form>
</div>
@endsection
