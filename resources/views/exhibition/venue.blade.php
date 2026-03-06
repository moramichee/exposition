@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | Venue Information')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Venue Information</p>
            <h1 class="page-title">ExCel London Hotel, London</h1>

            <div class="split">
                @foreach($infoCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 2 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M12 20s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10ZM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M8 3v3M16 3v3M4 8h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </span>
                        <h2>{{ $item->title }}</h2>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                        @if(!empty($item->secondary_content))
                            <p>{{ $item->secondary_content }}</p>
                        @endif
                        @if(!empty($item->extra_content))
                            <p>{{ $item->extra_content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="map-wrap">
                <iframe
                    title="ExCel London location map"
                    src="https://www.google.com/maps?q=ExCeL+London&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <h2 class="section-title">Transport and accommodation support</h2>
            <div class="feature-grid">
                @foreach($supportCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 3 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M3 16h18M5 16l1.5-6h11L19 16M7 16a1.5 1.5 0 1 0 3 0m4.5 0a1.5 1.5 0 1 0 3 0M6.5 10l2.5-3h6l2.5 3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($loop->index % 3 === 1)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M4 11.5h16M6 4h12a1 1 0 0 1 1 1v14H5V5a1 1 0 0 1 1-1Zm3 8v4m6-4v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M3 12h4l2 5 4-10 2 5h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
@endsection
