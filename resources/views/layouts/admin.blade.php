{{-- Gabarit de l'espace administration --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Token CSRF utilisé par les formulaires --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration — CESIZen')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Surcharges visuelles spécifiques à l'admin --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('css')
</head>
<body>
    {{-- En-tête admin avec couleur distincte du site public --}}
    <header class="site-header admin-header">
        <nav class="nav-container">
            <a href="{{ route('admin.dashboard') }}" class="nav-logo">CESIZen Admin</a>
            <ul class="nav-links">
                <li><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                <li><a href="{{ route('admin.information.index') }}">Informations</a></li>
                <li><a href="{{ route('admin.breathing.index') }}">Respiration</a></li>
                <li><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
                {{-- Lien de retour vers le site public --}}
                <li><a href="{{ route('home') }}">Voir le site</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="nav-btn">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        {{-- Message de succès flash --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Erreurs de validation --}}
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Contenu spécifique à chaque vue admin --}}
        @yield('content')
    </main>

    <footer class="site-footer">
        <p>CESIZen Administration &copy; {{ date('Y') }}</p>
    </footer>

    @yield('scripts')
</body>
</html>
