@extends('layouts.admin')

@section('title', 'Admin | Modifier un element')

@section('content')
    <h1 class="page-title">Modifier un element</h1>

    <article class="card admin-card">
        <form method="POST" action="{{ route('admin.elements.update', $element) }}" class="admin-form" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.elements._form', ['submitLabel' => 'Mettre a jour'])
        </form>
    </article>
@endsection
