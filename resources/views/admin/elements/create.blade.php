@extends('layouts.admin')

@section('title', 'Admin | Ajouter un element')

@section('content')
    @php($selectedPage = old('page', $element->page))
    @php($selectedSection = old('section', $element->section))
    @php($selectedPageLabel = is_string($selectedPage) && array_key_exists($selectedPage, $pageLabels) ? $pageLabels[$selectedPage] : null)
    @php($selectedSectionLabel = is_string($selectedPage) && is_string($selectedSection) && isset($pageSectionMap[$selectedPage][$selectedSection]) ? $pageSectionMap[$selectedPage][$selectedSection] : null)

    <h1 class="page-title">Ajouter un element</h1>

    @if($selectedPageLabel && $selectedSectionLabel)
        <p class="muted admin-context">
            Contexte: Page {{ $selectedPageLabel }} / {{ $selectedSectionLabel }}
        </p>
    @endif

    <article class="card admin-card">
        <form method="POST" action="{{ route('admin.elements.store') }}" class="admin-form" enctype="multipart/form-data">
            @include('admin.elements._form', ['submitLabel' => 'Ajouter'])
        </form>
    </article>
@endsection
