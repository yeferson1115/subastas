<x-public-layout title="{{ $auction->name }} | BidFixx" :categories="\App\Models\Category::with('subcategories')->orderBy('name')->get()">
@php $images = is_array($auction->images) ? $auction->images : []; $image = $images[0] ?? 'images/auction-products/1275010a-5b99-4302-8a1a-1ad3d49868ad.png'; $current = $auction->bids->first()?->amount ?? $auction->base_price; @endphp
<section class="container py-5">
    @if(session('status'))<div class="alert alert-success rounded-4">{{ session('status') }}</div>@endif
    <div class="row g-4">
        <div class="col-lg-7"><img src="{{ asset($image) }}" class="img-fluid rounded-5 shadow" alt="{{ $auction->name }}"></div>
        <div class="col-lg-5"><div class="card form-card"><div class="card-body p-4 p-lg-5"><span class="badge rounded-pill badge-status mb-3">{{ $auction->auction_status }}</span><h1 class="fw-black mb-3">{{ $auction->name }}</h1><p class="text-muted">{{ $auction->detail }}</p><dl class="row"><dt class="col-5">Categoría</dt><dd class="col-7">{{ $auction->category?->name }} / {{ $auction->subcategory?->name }}</dd><dt class="col-5">Ubicación</dt><dd class="col-7">{{ $auction->location }}</dd><dt class="col-5">Cierre</dt><dd class="col-7">{{ $auction->auction_end_date?->format('d/m/Y') }} {{ $auction->auction_end_time }}</dd><dt class="col-5">Oferta actual</dt><dd class="col-7 fw-bold text-brand-blue">${{ number_format((float) $current, 0, ',', '.') }}</dd></dl>
        @auth
            @if(auth()->user()->user_type === \App\Models\User::TYPE_BIDDER || auth()->user()->hasRole(\App\Models\User::TYPE_BIDDER))
                <form method="POST" action="{{ route('public.auctions.bid', $auction->slug) }}" class="mt-4">@csrf<div class="mb-3"><label class="form-label fw-bold">Tu oferta</label><input type="number" step="1000" min="{{ (float)$current + 1 }}" name="amount" class="form-control form-control-lg @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>@error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Comentario opcional</label><textarea name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea></div><button class="btn btn-brand btn-lg rounded-pill w-100" type="submit">Enviar oferta</button></form>
            @else
                <a href="{{ route('index') }}" class="btn btn-outline-brand rounded-pill w-100 mt-3">Ir al panel administrativo</a>
            @endif
        @else
            <a href="{{ route('register.bidder') }}" class="btn btn-brand btn-lg rounded-pill w-100 mt-3">Regístrate para ofertar</a><a href="{{ route('login') }}" class="btn btn-outline-brand rounded-pill w-100 mt-2">Ya tengo cuenta</a>
        @endauth
        </div></div></div>
    </div>
</section>
</x-public-layout>
