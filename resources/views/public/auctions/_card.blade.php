@php
    $images = is_array($auction->images) ? $auction->images : [];
    $image = $images[0] ?? 'images/auction-products/1275010a-5b99-4302-8a1a-1ad3d49868ad.png';
    $current = $auction->highestBid->first()?->amount ?? $auction->base_price;
@endphp
<div class="card auction-card">
    <img src="{{ asset($image) }}" class="card-img-top auction-card-img" alt="{{ $auction->name }}">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between gap-2 mb-3">
            <span class="badge rounded-pill badge-status">{{ $auction->auction_status }}</span>
            <span class="small text-muted">{{ $auction->category?->name }}</span>
        </div>
        <h3 class="h5 fw-bold mb-2">{{ $auction->name }}</h3>
        <p class="text-muted small mb-3">{{ Str::limit($auction->detail ?? 'Subasta disponible con condiciones y ficha técnica para revisar.', 96) }}</p>
        <div class="d-flex justify-content-between align-items-end gap-3">
            <div>
                <small class="text-muted d-block">Oferta actual</small>
                <strong class="text-brand-blue">${{ number_format((float) $current, 0, ',', '.') }}</strong>
            </div>
            <a href="{{ route('public.auctions.show', $auction->slug) }}" class="btn btn-brand rounded-pill px-3">Ver lote</a>
        </div>
    </div>
</div>
