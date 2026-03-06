@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | About')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">About the Exhibition</p>
            <h1 class="page-title">Why this exhibition exists</h1>

            <div class="split">
                @foreach($missionCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 4 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M4 6h16v12H4zM8 10h8M8 14h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($loop->index % 4 === 1)
                                <svg class="icon" viewBox="0 0 24 24"><path d="m4 12 4-4 4 4 4-4 4 4M4 17h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($loop->index % 4 === 2)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M12 20V8M12 8l-4 4M12 8l4 4M5 20h14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M4 12.5 9.5 18 20 7.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </span>
                        <h2>{{ $item->title }}</h2>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <h2 class="section-title">Organizer biography</h2>
            @foreach($biographyCards as $item)
                <article class="card">
                    <span class="icon-badge" aria-hidden="true">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-7 8a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <h3>{{ $item->title }}</h3>
                    @if(!empty($item->content))
                        <p>{{ $item->content }}</p>
                    @endif
                    @if(!empty($item->extra_content))
                        <p>{{ $item->extra_content }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </section>
@endsection
