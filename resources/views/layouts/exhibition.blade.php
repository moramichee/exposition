<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Global Alliance Exhibition')</title>
    <meta name="description" content="@yield('meta_description', 'Alliance Expo Monde / Global Alliance Exhibition in London, June 8-14, 2026.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-main">Alliance Expo Monde</span>
                <span class="brand-sub">Global Alliance Exhibition</span>
            </a>

            <nav class="site-nav" aria-label="Main">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5M6 9.5V20h12V9.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Home
                </a>
                <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4a8 8 0 1 0 8 8M12 10.5v6M12 7.5h.01" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    About
                </a>
                <a href="{{ route('artists') }}" @class(['active' => request()->routeIs('artists')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19V8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v11M9 6V4m6 2V4M4 19h16M8 12h8M8 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Artists
                </a>
                <a href="{{ route('venue') }}" @class(['active' => request()->routeIs('venue')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10ZM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Localisation
                </a>
                <a href="{{ route('press') }}" @class(['active' => request()->routeIs('press')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Press
                </a>
                <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>
                    <svg class="icon nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4zM4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Contact
                </a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-wrap">
            <div>
                <p class="footer-title">Global Alliance Exhibition</p>
                <p>
                    <svg class="icon footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v3M16 3v3M4 8h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    June 8-14, 2026 | 10:00-16:00 | ExCel London Hotel
                </p>
                <p>
                    <svg class="icon footer-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4zM4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Contact: <a href="mailto:pinault12art@gmail.com">pinault12art@gmail.com</a> |
                    <a href="https://wa.me/447405854019" target="_blank" rel="noopener">+44 7405 854019</a>
                </p>
            </div>

            <nav class="legal-nav" aria-label="Legal">
                <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
                <a href="{{ route('legal.terms') }}">Terms & Conditions</a>
                <a href="{{ route('legal.cookies') }}">Cookie Notice</a>
                <a href="{{ route('legal.copyright') }}">Copyright</a>
                <a href="{{ route('admin.login') }}">Admin</a>
            </nav>
        </div>
    </footer>
</body>
</html>
