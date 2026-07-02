{{-- Formulaire d'inscription d'un nouveau compte --}}
@extends('layouts.app')

@section('title', 'Inscription — CESIZen')

@section('content')
<div class="container container-small">
    <h1>Inscription</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe (min. 8 caractères)</label>
            <input type="password" id="password" name="password" required>
        </div>

        {{-- Le champ password_confirmation est comparé à password par la règle "confirmed" --}}
        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p class="form-footer">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
</div>
@endsection
