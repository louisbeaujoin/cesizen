{{-- Formulaire de connexion --}}
@extends('layouts.app')

@section('title', 'Connexion — CESIZen')

@section('content')
<div class="container container-small">
    <h1>Connexion</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Adresse email</label>
            {{-- old('email') restitue la valeur saisie en cas d'erreur --}}
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            {{-- Case "Se souvenir de moi" : active le cookie de session longue durée --}}
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label>
        </div>

        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p class="form-footer">Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
</div>
@endsection
