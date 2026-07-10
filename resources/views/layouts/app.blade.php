{{-- Gabarit principal du site public --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Token CSRF utilisé par Axios et les formulaires --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CESIZen')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- CSS optionnel injecté par les vues enfants --}}
    @yield('css')
</head>
<body>
    <header class="site-header">
        <nav class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo">CESIZen</a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li><a href="{{ route('information.index') }}">Informations</a></li>
                <li><a href="{{ route('breathing.index') }}">Respiration</a></li>
                <li><a href="{{ route('tickets.create') }}">Support</a></li>
                {{-- Liens affichés uniquement si l'utilisateur est connecté --}}
                @auth
                    {{-- Lien admin visible uniquement pour les administrateurs --}}
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">Administration</a></li>
                    @endif
                    <li><a href="{{ route('profile') }}">Mon profil</a></li>
                    <li>
                        {{-- Déconnexion via POST pour protéger contre le CSRF --}}
                        <form action="{{ route('logout') }}" method="POST" class="inline-form">
                            @csrf
                            <button type="submit" class="nav-btn">Déconnexion</button>
                        </form>
                    </li>
                {{-- Liens affichés uniquement pour les visiteurs non connectés --}}
                @else
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main class="main-content">
        {{-- Message de succès flash (ex : après création ou modification) --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Erreurs de validation affichées sous forme de liste --}}
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Contenu spécifique à chaque vue --}}
        @yield('content')
    </main>

    <footer class="site-footer">
        <p>CESIZen &copy; {{ date('Y') }} — Santé mentale et gestion du stress</p>
    </footer>

    {{-- Scripts optionnels injectés par les vues enfants --}}
    @yield('scripts')
</body>
</html>
