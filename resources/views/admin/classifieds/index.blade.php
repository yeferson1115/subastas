@extends('layouts.app')
@section('title', 'Clasificados')
@section('page_title', 'Clasificados')
@section('page_subtitle', 'Listado')
@section('content')
<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="col-md-auto me-auto"><h5 class="card-title mb-0">Clasificados</h5></div>
        <div class="col-md-auto ms-auto"><a class="btn btn-primary" href="{{ route('classifieds.create') }}"><i class="icon-base ti tabler-plus icon-sm"></i> Nuevo clasificado</a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead><tr><th>Imagen</th><th>Título</th><th>Usuario</th><th>Categoría</th><th>Precio</th><th>Contacto</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($classifieds as $classified)
                        <tr>
                            <td>@if(! empty($classified->images[0]))<img src="{{ asset($classified->images[0]) }}" alt="{{ $classified->title }}" class="rounded" width="64" height="64" style="object-fit:cover;">@else<span class="badge bg-secondary">Sin imagen</span>@endif</td>
                            <td>{{ $classified->title }}<br><small><code>{{ $classified->slug }}</code></small></td>
                            <td>{{ trim(($classified->user?->name ?? '') . ' ' . ($classified->user?->last_name ?? '')) ?: $classified->user?->email }}</td>
                            <td>{{ $classified->category?->name }} @if($classified->subcategory) / {{ $classified->subcategory->name }} @endif</td>
                            <td>${{ number_format((float) $classified->price, 2, ',', '.') }}</td>
                            <td>{{ $classified->contact }}</td>
                            <td><a href="{{ route('classifieds.edit', $classified) }}" class="btn btn-sm btn-secondary">Editar</a> <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $classified->id }}" data-url="{{ route('classifieds.destroy', $classified) }}">Eliminar</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $classifieds->links() }}
    </div>
</div>
@endsection
