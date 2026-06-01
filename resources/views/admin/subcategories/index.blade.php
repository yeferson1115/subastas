@extends('layouts.app')

@section('title', 'Subcategorías')
@section('page_title', 'Subcategorías')
@section('page_subtitle', 'Listado')
@section('content')
<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="col-md-auto me-auto"><h5 class="card-title mb-0">Subcategorías</h5></div>
        <div class="col-md-auto ms-auto"><a class="btn btn-primary" href="{{ route('subcategories.create') }}"><i class="icon-base ti tabler-plus icon-sm"></i> Nueva subcategoría</a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead><tr><th>Imagen</th><th>Nombre</th><th>Categoría</th><th>Slug</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($subcategories as $subcategory)
                        <tr>
                            <td><img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->name }}" class="rounded" width="64" height="64" style="object-fit: cover;"></td>
                            <td>{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->category?->name }}</td>
                            <td><code>{{ $subcategory->slug }}</code></td>
                            <td>
                                <a href="{{ route('subcategories.edit', $subcategory) }}" class="btn btn-sm btn-secondary">Editar</a>
                                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $subcategory->id }}" data-url="{{ route('subcategories.destroy', $subcategory) }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $subcategories->links() }}
    </div>
</div>
@endsection
