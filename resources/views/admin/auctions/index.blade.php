@extends('layouts.app')

@section('title', 'Subastas')
@section('page_title', 'Subastas')
@section('page_subtitle', 'Activas y terminadas')
@section('content')
@php
    $statusLabels = [
        'active' => 'Activas',
        'finished' => 'Terminadas',
        'scheduled' => 'Programadas',
        'all' => 'Todas',
    ];
@endphp

<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="col-md-auto me-auto">
            <h5 class="card-title mb-0">Módulo de subastas</h5>
            <small class="text-muted">Consulta el estado, pujas, ganador o ganador provisional de cada subasta.</small>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach($statusLabels as $key => $label)
                <a href="{{ route('admin.auctions.index', ['status' => $key]) }}" class="btn btn-{{ $status === $key ? 'primary' : 'outline-primary' }}">
                    {{ $label }}
                    <span class="badge bg-{{ $status === $key ? 'light text-primary' : 'primary' }} ms-1" style="color:#fff">{{ $counts[$key] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead>
                    <tr>
                        <th>Subasta</th>
                        <th>Subastador</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Pujas</th>
                        <th>Ganador / Va ganando</th>
                        <th>Valor final / actual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auctions as $auction)
                        @php
                            $highestBid = $auction->bids->first();
                            $winnerLabel = $highestBid?->user
                                ? trim($highestBid->user->name . ' ' . $highestBid->user->last_name)
                                : null;
                            $currentValue = $highestBid?->amount ?? $auction->base_price;
                            $badgeClass = $auction->is_finished ? 'secondary' : ($auction->is_active ? 'success' : 'info');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $auction->name }}</strong><br>
                                <small class="text-muted">{{ $auction->category?->name }} @if($auction->subcategory) / {{ $auction->subcategory->name }} @endif</small>
                            </td>
                            <td>{{ $auction->auctioneer?->company_name ?: $auction->auctioneer?->name }}</td>
                            <td><span class="badge bg-{{ $badgeClass }}">{{ $auction->auction_status }}</span></td>
                            <td>{{ $auction->starts_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $auction->ends_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $auction->bids->count() }}</td>
                            <td>
                                @if($winnerLabel)
                                    <span class="fw-semibold">{{ $winnerLabel }}</span><br>
                                    <small class="text-muted">{{ $highestBid->user->email }}</small>
                                @else
                                    <span class="text-muted">Sin pujas</span>
                                @endif
                            </td>
                            <td>${{ number_format((float) $currentValue, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.auctions.show', $auction) }}" class="btn btn-sm btn-primary">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No hay subastas para el filtro seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $auctions->links() }}
    </div>
</div>
@endsection
