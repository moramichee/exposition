@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | Artists & Exhibitors')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Artists / Exhibitors</p>
            <h1 class="page-title">Featured international exhibitors</h1>
            <p class="lead">
                The final lineup includes emerging and underrepresented artists selected for originality, technical quality, and global relevance.
            </p>

            <div class="artist-grid">
                @foreach($artistCards as $item)
                    <article class="artist-card">
                        @php($src = !empty($item->image_path) ? (str_starts_with($item->image_path, 'http') ? $item->image_path : asset('storage/'.$item->image_path)) : 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80')
                        <img src="{{ $src }}" alt="{{ $item->image_alt ?? $item->title }}">
                        <div>
                            <h2>{{ $item->title }}</h2>
                            @if(!empty($item->secondary_content))
                                <p class="muted line-icon"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $item->secondary_content }}</p>
                            @endif
                            @if(!empty($item->content))
                                <p>{{ $item->content }}</p>
                            @endif
                            @if(!empty($item->extra_content))
                                <p class="line-icon"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7.5h14M5 12h14M5 16.5h10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><strong>{{ $item->extra_content }}</strong></p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
