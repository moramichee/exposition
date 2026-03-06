@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | Press & Media')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Press & Media</p>
            <h1 class="page-title">Media visibility and credibility assets</h1>

            <div class="partner-grid">
                @foreach($partnerCards as $item)
                    <article class="partner-card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 2 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M3 16h18M5 16l1.5-6h11L19 16M7 16a1.5 1.5 0 1 0 3 0m4.5 0a1.5 1.5 0 1 0 3 0M6.5 10l2.5-3h6l2.5 3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M3 13l9-4 9 4-9 4-9-4Zm4-2.2V8.5a5 5 0 0 1 10 0v2.3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </span>
                        <span class="partner-logo">{{ $item->title }}</span>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="split">
                @foreach($resourceCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            @if($loop->index % 2 === 0)
                                <svg class="icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="icon" viewBox="0 0 24 24"><path d="M7 12.5 10 16l7-8M12 3l7 3v6c0 4.2-2.5 7.8-7 9-4.5-1.2-7-4.8-7-9V6z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </span>
                        <h2>{{ $item->title }}</h2>
                        @if(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                        @php($listLines = !empty($item->extra_content) ? preg_split('/\r\n|\r|\n/', trim($item->extra_content)) : [])
                        @if(!empty($listLines))
                            <ul class="list">
                                @foreach($listLines as $line)
                                    @if(!empty($line))
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                        @if(str_contains(strtolower($item->title), 'download'))
                            <a class="btn btn-primary" href="{{ route('press.release.pdf') }}">Download Press Release (PDF)</a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
