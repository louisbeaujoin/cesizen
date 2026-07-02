{{-- Page de profil : modification des informations et du mot de passe --}}
@extends('layouts.app')

@section('title', 'Mon profil — CESIZen')

@section('content')
<div class="container container-small">
    <h1>Mon profil</h1>

    {{-- Formulaire de mise à jour du nom et de l'email --}}
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        {{-- Simule une requête PUT car les formulaires HTML ne supportent que GET/POST --}}
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom</label>
            {{-- old() restitue la valeur saisie en cas d'erreur, sinon affiche la valeur actuelle --}}
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <button type="submit" class="btn">Mettre à jour</button>
    </form>

    <hr>

    {{-- Formulaire de changement de mot de passe (séparé pour éviter de vider les champs du profil) --}}
    <h2>Changer le mot de passe</h2>

    <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn">Changer le mot de passe</button>
    </form>
</div>
@endsection
