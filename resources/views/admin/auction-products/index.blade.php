@extends('layouts.app')

@section('title', 'Productos a subastar')
@section('page_title', 'Productos a subastar')
@section('page_subtitle', 'Listado')
@section('content')
<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="col-md-auto me-auto"><h5 class="card-title mb-0">Productos a subastar</h5></div>
        <div class="col-md-auto ms-auto"><a class="btn btn-primary" href="{{ route('auction-products.create') }}"><i class="icon-base ti tabler-plus icon-sm"></i> Nuevo producto</a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead>
                    <tr><th>Imagen</th><th>Producto</th><th>Subastador</th><th>Categoría</th><th>Tipo</th><th>Inicio</th><th>Fin</th><th>Precio base</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                @if(! empty($product->images[0]))
                                    <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}" class="rounded" width="64" height="64" style="object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">Sin imagen</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}<br><small><code>{{ $product->slug }}</code></small></td>
                            <td>{{ $product->auctioneer?->company_name ?: $product->auctioneer?->name }}</td>
                            <td>{{ $product->category?->name }} @if($product->subcategory) / {{ $product->subcategory->name }} @endif</td>
                            <td>{{ $product->product_type }}</td>
                            <td>{{ $product->auction_start_date?->format('d/m/Y') }} {{ substr($product->auction_start_time, 0, 5) }}</td>
                            <td>{{ $product->auction_end_date?->format('d/m/Y') }} {{ substr($product->auction_end_time, 0, 5) }}</td>
                            <td>${{ number_format((float) $product->base_price, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('auction-products.edit', $product) }}" class="btn btn-sm btn-secondary">Editar</a>
                                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $product->id }}" data-url="{{ route('auction-products.destroy', $product) }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </div>
</div>
@endsection
