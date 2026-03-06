@extends('layouts.admin')

@section('title', 'Admin | Elements')

@section('content')
    <div class="admin-heading">
        <h1 class="page-title">Elements dynamiques</h1>
        <a href="{{ route('admin.elements.create') }}" class="btn btn-primary">Nouvel element</a>
    </div>

    @foreach($pageLabels as $pageKey => $pageLabel)
        <section class="admin-block">
            <h2>Page: {{ $pageLabel }}</h2>

            @foreach($pageSectionMap[$pageKey] as $sectionKey => $sectionLabel)
                <div class="admin-section-header">
                    <h3 class="admin-section-title">{{ $sectionLabel }}</h3>
                    <a href="{{ route('admin.elements.create', ['page' => $pageKey, 'section' => $sectionKey]) }}" class="btn btn-primary btn-sm">
                        Ajouter un element
                    </a>
                </div>
                @php($sectionElements = $elements->where('page', $pageKey)->where('section', $sectionKey))

                @if($sectionElements->isEmpty())
                    <p class="muted">Aucun element dans cette section.</p>
                @else
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Ordre</th>
                                    <th>Titre</th>
                                    <th>Contenu</th>
                                    <th>Image</th>
                                    <th>Actif</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectionElements as $element)
                                    <tr>
                                        <td>{{ $element->sort_order }}</td>
                                        <td>{{ $element->title }}</td>
                                        <td>
                                            <div>{{ $element->content }}</div>
                                            @if($element->secondary_content)
                                                <div class="muted">{{ $element->secondary_content }}</div>
                                            @endif
                                            @if($element->extra_content)
                                                <div class="muted">{{ $element->extra_content }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($element->image_path)
                                                @php($src = str_starts_with($element->image_path, 'http') ? $element->image_path : asset('storage/'.$element->image_path))
                                                <img src="{{ $src }}" alt="{{ $element->image_alt ?? $element->title }}" class="admin-thumb">
                                            @else
                                                <span class="muted">Aucune</span>
                                            @endif
                                        </td>
                                        <td>{{ $element->is_active ? 'Oui' : 'Non' }}</td>
                                        <td class="admin-actions">
                                            <a href="{{ route('admin.elements.edit', $element) }}" class="btn btn-primary btn-sm">Modifier</a>
                                            <form method="POST" action="{{ route('admin.elements.destroy', $element) }}" onsubmit="return confirm('Supprimer cet element ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </section>
    @endforeach
@endsection
