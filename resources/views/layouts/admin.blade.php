<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <header class="site-header admin-header">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('admin.elements.index') }}">
                <span class="brand-main">Administration</span>
                <span class="brand-sub">Global Alliance Exhibition</span>
            </a>

            <nav class="site-nav" aria-label="Admin navigation">
                <a href="{{ route('admin.elements.index') }}" @class(['active' => request()->routeIs('admin.elements.index')])>Elements</a>
                <a href="{{ route('admin.elements.create') }}" @class(['active' => request()->routeIs('admin.elements.create')])>Ajouter</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">Voir le site</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Se deconnecter</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="section admin-main">
        <div class="container">
            @if(session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
