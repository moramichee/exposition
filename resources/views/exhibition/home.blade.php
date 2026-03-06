@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | Home')
@section('meta_description', 'International art exhibition in London. Discover dates, venue and registration details for Alliance Expo Monde / Global Alliance Exhibition.')

@section('content')
    <section class="hero">
        <div class="hero-backdrop" aria-hidden="true"></div>
        <div class="container hero-content">
            <p class="eyebrow">Alliance Expo Monde / Global Alliance Exhibition</p>
            <h1>A global stage for emerging and underrepresented artists.</h1>
            <p class="lead">
                Join an international exhibition designed to give rising talent visibility, market access, and a serious platform in London.
            </p>

            <div class="meta-grid">
                @foreach($metaItems as $item)
                    <article class="meta-item">
                        <div class="meta-head">
                            <span class="icon-chip" aria-hidden="true">
                                @if($loop->index % 3 === 0)
                                    <svg class="icon" viewBox="0 0 24 24"><path d="M8 3v3M16 3v3M4 8h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @elseif($loop->index % 3 === 1)
                                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 20s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10ZM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @else
                                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-7 8a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @endif
                            </span>
                            <h2>{{ $item->title }}</h2>
                        </div>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                        @if(!empty($item->secondary_content))
                            <p>{{ $item->secondary_content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="actions">
                <a class="btn btn-primary" href="{{ route('contact') }}">Register / Attend</a>
                <a class="btn btn-secondary" href="{{ route('about') }}">Learn More</a>
                <button type="button" class="btn btn-secondary" id="toggle-invited-artists" aria-expanded="false" aria-controls="invited-artists-panel">
                    See more
                </button>
            </div>

            <div class="hero-extra" id="invited-artists-panel" hidden>
                <h2>Invited artists:</h2>
                <ul class="hero-list">
                    <li>Sergiu Ciochina</li>
                    <li>Djabril Boukhenaissi</li>
                    <li>Guglielmo Castelli</li>
                    <li>Paola Pivi</li>
                    <li>Markus Lupertz</li>
                    <li>Anselm Kiefer</li>
                </ul>
                <p class="hero-note">
                    Note: This list will be updated once registrations are closed.
                    Registrations will be closed no later than May 18, 2026.
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">International readiness highlights</h2>
            <div class="feature-grid">
                @foreach($highlightItems as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 3 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($loop->index % 3 === 1)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M7 12.5 10 16l7-8M12 3l7 3v6c0 4.2-2.5 7.8-7 9-4.5-1.2-7-4.8-7-9V6z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M2.5 17.5h19M6 17.5v-6l6-4 6 4v6M9 17.5v-3h6v3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </span>
                        <h3>{{ $item->title }}</h3>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        (function () {
            const toggleButton = document.getElementById('toggle-invited-artists');
            const panel = document.getElementById('invited-artists-panel');
            if (!toggleButton || !panel) return;

            toggleButton.addEventListener('click', function () {
                const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
                toggleButton.setAttribute('aria-expanded', String(!isExpanded));
                panel.hidden = isExpanded;
                toggleButton.textContent = isExpanded ? 'See more' : 'See less';
            });
        })();
    </script>
@endsection
