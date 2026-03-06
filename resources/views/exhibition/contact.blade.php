@extends('layouts.exhibition')

@section('title', 'Global Alliance Exhibition | Contact & Registration')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Contact</p>
            <h1 class="page-title">Registration and direct contact</h1>

            <div class="split">
                @foreach($channelCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M4 6h16v12H4zM4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h2>{{ $item->title }}</h2>
                        @if(!empty($item->content))
                            @php($emailRaw = trim((string) str_ireplace('Email:', '', $item->content)))
                            <p><strong>Email:</strong> <a href="mailto:{{ $emailRaw }}">{{ $emailRaw }}</a></p>
                        @endif
                        @if(!empty($item->secondary_content))
                            @php($phoneRaw = trim((string) str_ireplace('WhatsApp:', '', $item->secondary_content)))
                            @php($phoneDigits = preg_replace('/[^0-9]/', '', $phoneRaw))
                            <p><strong>WhatsApp:</strong> <a href="https://wa.me/{{ $phoneDigits }}" target="_blank" rel="noopener">{{ $phoneRaw }}</a></p>
                        @endif
                        @if(!empty($item->extra_content))
                            <p><strong>Partners:</strong> {{ str_ireplace('Partners:', '', $item->extra_content) }}</p>
                        @endif
                        <p>For faster processing, submit the form and confirm via WhatsApp.</p>
                    </article>
                @endforeach

                @foreach($termsCards as $item)
                    <article class="card">
                        <span class="icon-badge" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M8 3v3M16 3v3M4 8h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h2>{{ $item->title }}</h2>
                        @php($listLines = !empty($item->extra_content) ? preg_split('/\r\n|\r|\n/', trim($item->extra_content)) : [])
                        @if(!empty($listLines))
                            <ul class="list">
                                @foreach($listLines as $line)
                                    @if(!empty($line))
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @elseif(!empty($item->content))
                            <p>{{ $item->content }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            @if (session('status'))
                <div class="alert-success" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <article class="card form-card">
                <span class="icon-badge" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <h2>Registration form</h2>
                <form method="post" action="{{ route('register') }}" novalidate>
                    @csrf
                    <div class="form-grid">
                        <label>
                            Full name
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')<small class="error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Country and city
                            <input type="text" name="country_city" value="{{ old('country_city') }}" required>
                            @error('country_city')<small class="error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Types of artworks
                            <input type="text" name="artwork_types" value="{{ old('artwork_types') }}" required>
                            @error('artwork_types')<small class="error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Gender
                            <select name="gender" required>
                                <option value="">Select one</option>
                                @foreach (['Female', 'Male', 'Non-binary', 'Prefer not to say'] as $gender)
                                    <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ $gender }}</option>
                                @endforeach
                            </select>
                            @error('gender')<small class="error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            Email
                            <input type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<small class="error">{{ $message }}</small>@enderror
                        </label>

                        <label>
                            WhatsApp number
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required>
                            @error('whatsapp')<small class="error">{{ $message }}</small>@enderror
                        </label>
                    </div>

                    <button class="btn btn-primary" type="submit">Submit Registration</button>
                </form>
            </article>
        </div>
    </section>
@endsection
