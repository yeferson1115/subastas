@extends('layouts.app')

@section('title', 'Categorías')
@section('page_title', 'Categorías')
@section('page_subtitle', 'Editar')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Editar Categoría</h5>
    <form method="POST" action="{{ route('categories.update', $category) }}" class="card-body" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-6">
            <div class="col-md-6">
                <label class="form-label" for="name">Nombre</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                <div class="form-text">Slug actual: <code>{{ $category->slug }}</code>. Se recalculará al actualizar el nombre.</div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="image">Imagen</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror">
                <div class="form-text">Déjala vacía para conservar la imagen actual en <code>public/images/categories</code>.</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if ($category->image)
            <div class="pt-6">
                <p class="mb-2 fw-semibold">Imagen actual</p>
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="rounded" width="120" height="120" style="object-fit: cover;">
            </div>
        @endif

        <div class="pt-6 d-flex gap-2">
            <button class="btn btn-primary mb-5">Actualizar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary mb-5">Cancelar</a>
        </div>
    </form>
</div>
@endsection
