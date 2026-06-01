@extends('layouts.app')

@section('title', 'Categorías')
@section('page_title', 'Categorías')
@section('page_subtitle', 'Listado')
@section('content')
<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
            <h5 class="card-title mb-0 text-md-start text-center pb-md-0 pb-6">Categorías</h5>
        </div>
        @can('Crear Categorias')
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
            <div class="dt-buttons btn-group flex-wrap mb-0">
                <a class="btn create-new btn-primary" href="{{ route('categories.create') }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="icon-base ti tabler-plus icon-sm"></i>
                        <span class="d-none d-sm-inline-block">Nueva categoría</span>
                    </span>
                </a>
            </div>
        </div>
        @endcan
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="rounded" width="64" height="64" style="object-fit: cover;">
                            </td>
                            <td>{{ $category->name }}</td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                @can('Editar Categorias')
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-secondary">Editar</a>
                                @endcan
                                @can('Eliminar Categorias')
                                <button class="btn btn-danger btn-sm btnDelete"
                                        data-id="{{ $category->id }}"
                                        data-url="{{ route('categories.destroy', $category) }}">
                                    Eliminar
                                </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </div>
</div>
@endsection
