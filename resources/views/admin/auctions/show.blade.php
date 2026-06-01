@extends('layouts.app')

@section('title', 'Detalle de subasta')
@section('page_title', 'Detalle de subasta')
@section('page_subtitle', $auction->name)
@section('content')
@php
    $highestBid = $auction->bids->first();
    $winnerLabel = $highestBid?->user ? trim($highestBid->user->name . ' ' . $highestBid->user->last_name) : null;
    $currentValue = $highestBid?->amount ?? $auction->base_price;
    $badgeClass = $auction->is_finished ? 'secondary' : ($auction->is_active ? 'success' : 'info');
    $resultLabel = $auction->is_finished ? 'Ganador' : 'Va ganando';
@endphp

<div class="mb-3">
    <a href="{{ route('admin.auctions.index') }}" class="btn btn-outline-secondary btn-sm">← Volver a subastas</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $auction->name }}</h5>
                        <small class="text-muted">{{ $auction->category?->name }} @if($auction->subcategory) / {{ $auction->subcategory->name }} @endif</small>
                    </div>
                    <span class="badge bg-{{ $badgeClass }}">{{ $auction->auction_status }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Subastador</small>
                        <strong>{{ $auction->auctioneer?->company_name ?: $auction->auctioneer?->name }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Inicio</small>
                        <strong>{{ $auction->starts_at?->format('d/m/Y H:i') }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Fin</small>
                        <strong>{{ $auction->ends_at?->format('d/m/Y H:i') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Precio base</small>
                        <strong>${{ number_format((float) $auction->base_price, 2, ',', '.') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">{{ $auction->is_finished ? 'Valor final' : 'Valor actual' }}</small>
                        <strong>${{ number_format((float) $currentValue, 2, ',', '.') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Total de pujas</small>
                        <strong>{{ $auction->bids->count() }}</strong>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Detalle</small>
                        <p class="mb-0">{{ $auction->detail }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header border-bottom"><h5 class="card-title mb-0">Resultado</h5></div>
            <div class="card-body">
                <small class="text-muted d-block">{{ $resultLabel }}</small>
                @if($winnerLabel)
                    <h5 class="mb-1">{{ $winnerLabel }}</h5>
                    <div class="text-muted mb-3">{{ $highestBid->user->email }}</div>
                    <div class="alert alert-{{ $auction->is_finished ? 'secondary' : 'success' }} mb-0">
                        {{ $auction->is_finished ? 'Valor final' : 'Oferta líder' }}:
                        <strong>${{ number_format((float) $highestBid->amount, 2, ',', '.') }}</strong>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">Esta subasta todavía no tiene pujas registradas.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Pujas realizadas</h5>
        <small class="text-muted">Listado completo de ofertas con usuario, correo, valor y fecha.</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="datatables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Valor puja</th>
                        <th>Fecha</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auction->bids as $bid)
                        <tr class="{{ $loop->first ? 'table-success' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ trim($bid->user?->name . ' ' . $bid->user?->last_name) ?: 'Usuario eliminado' }}</td>
                            <td>{{ $bid->user?->email ?? 'N/A' }}</td>
                            <td>{{ $bid->user?->phone ?? 'N/A' }}</td>
                            <td>${{ number_format((float) $bid->amount, 2, ',', '.') }}</td>
                            <td>{{ $bid->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $bid->comment ?: 'Sin observación' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay pujas registradas para esta subasta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
