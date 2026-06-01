@extends('layouts.app')

@section('title', 'Categorías')
@section('page_title', 'Categorías')
@section('page_subtitle', 'Crear')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Nueva Categoría</h5>
    <form method="POST" action="{{ route('categories.store') }}" class="card-body" enctype="multipart/form-data">
        @csrf

        <div class="row g-6">
            <div class="col-md-6">
                <label class="form-label" for="name">Nombre</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                <div class="form-text">El slug se genera automáticamente desde el nombre.</div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="image">Imagen</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror" required>
                <div class="form-text">Se guardará en <code>public/images/categories</code>.</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="pt-6 d-flex gap-2">
            <button class="btn btn-primary mb-5">Guardar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary mb-5">Cancelar</a>
        </div>
    </form>
</div>
@endsection
