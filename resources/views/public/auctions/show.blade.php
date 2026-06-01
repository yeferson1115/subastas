<x-public-layout title="{{ $auction->name }} | BidFixx" :categories="\App\Models\Category::with('subcategories')->orderBy('name')->get()">
@php $images = is_array($auction->images) ? $auction->images : []; $image = $images[0] ?? 'images/auction-products/1275010a-5b99-4302-8a1a-1ad3d49868ad.png'; $current = $auction->bids->first()?->amount ?? $auction->base_price; @endphp
<section class="container py-5">
    @if(session('status'))<div class="alert alert-success rounded-4">{{ session('status') }}</div>@endif
    <div class="row g-4">
        <div class="col-lg-7">
            <img src="{{ asset($image) }}" class="img-fluid rounded-5 shadow" alt="{{ $auction->name }}">
            <h2>Detalle del lote</h2>
            {!! $auction->detail !!}
        </div>
        <div class="col-lg-5">
            <div class="card form-card">
                <div class="card-body p-4 p-lg-5">
                    <span class="badge rounded-pill badge-status mb-3">{{ $auction->auction_status }}</span>
                    <h1 class="fw-black mb-3">{{ $auction->name }}</h1>
                    <dl class="row">
                        <dt class="col-5">Categoría</dt>
                        <dd class="col-7">{{ $auction->category?->name }} / {{ $auction->subcategory?->name }}</dd>
                        <dt class="col-5">Ubicación</dt>
                        <dd class="col-7">{{ $auction->location }}</dd>
                        <dt class="col-5">Cierre</dt>
                        <dd class="col-7">{{ $auction->auction_end_date?->format('d/m/Y') }} {{ $auction->auction_end_time }}</dd>
                        @php
                            $details = is_array($auction->product_details)
                                ? $auction->product_details
                                : json_decode($auction->product_details ?? '{}', true);
                        @endphp

                        @if(!empty($details))
                            @foreach($details as $key => $value)
                                <dt class="col-5">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                </dt>
                                <dd class="col-7">
                                    {{ $value }}
                                </dd>
                            @endforeach
                        @endif
                        @if($auction->technical_sheet_path || $auction->terms_path)
                            <dt class="col-12 mt-3">
                                <span class="fw-bold text-brand-blue">Documentos</span>
                            </dt>

                            <dd class="col-12">
                                <div class="row g-3">
                                    @if($auction->technical_sheet_path)
                                        <div class="col-12">
                                            <a href="{{ asset($auction->technical_sheet_path) }}"
                                            target="_blank"
                                            class="text-decoration-none">
                                                <div class="d-flex align-items-center p-3 rounded-4 border bg-white shadow-sm">
                                                    <div class="me-3 fs-2 text-danger">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-brand-blue">
                                                            Ficha técnica
                                                        </div>
                                                        <small class="text-muted">
                                                            Ver documento técnico del lote
                                                        </small>
                                                    </div>
                                                    <i class="bi bi-arrow-right-circle-fill text-brand-cyan fs-4"></i>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if($auction->terms_path)
                                        <div class="col-12">
                                            <a href="{{ asset($auction->terms_path) }}"
                                            target="_blank"
                                            class="text-decoration-none">
                                                <div class="d-flex align-items-center p-3 rounded-4 border bg-white shadow-sm">
                                                    <div class="me-3 fs-2 text-primary">
                                                        <i class="bi bi-file-earmark-text-fill"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-brand-blue">
                                                            Términos y condiciones
                                                        </div>
                                                        <small class="text-muted">
                                                            Consultar condiciones de la subasta
                                                        </small>
                                                    </div>
                                                    <i class="bi bi-arrow-right-circle-fill text-brand-cyan fs-4"></i>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </dd>
                        @endif

                        <dt class="col-5">Oferta actual</dt>
                        <dd class="col-7 fw-bold text-brand-blue">${{ number_format((float) $current, 0, ',', '.') }}</dd>
                    </dl>
                        @auth
                            @if(auth()->user()->user_type === \App\Models\User::TYPE_BIDDER || auth()->user()->hasRole(\App\Models\User::TYPE_BIDDER))

                                @if($auction->auction_status === 'Activa')

                                    <form method="POST" action="{{ route('public.auctions.bid', $auction->slug) }}" class="mt-4">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tu oferta</label>
                                            <input
                                                type="number"
                                                min="{{ $current + 1 }}"
                                                name="amount"
                                                class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                                value="{{ old('amount') }}"
                                                required>

                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Comentario opcional</label>
                                            <textarea name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                                        </div>

                                        <button class="btn btn-brand btn-lg rounded-pill w-100" type="submit">
                                            Enviar oferta
                                        </button>
                                    </form>

                                @else

                                    <div class="auction-disabled-card mt-4">

                                        <div class="auction-disabled-icon">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>

                                        <h5 class="fw-bold text-brand-blue mb-2">
                                            Subasta no disponible para ofertar
                                        </h5>

                                        <p class="text-muted mb-3">
                                            Este lote actualmente se encuentra en estado
                                            <strong>{{ $auction->auction_status }}</strong>.
                                            Las ofertas estarán disponibles cuando la subasta sea activada.
                                        </p>

                                        <div class="auction-countdown">
                                            <div class="countdown-label">
                                                Tiempo para abrir
                                            </div>

                                            <div id="auctionCountdown"
                                                class="countdown-value"
                                                data-end="{{ $auction->auction_start_date?->format('Y-m-d') }} {{ $auction->auction_end_time }}">
                                                Calculando...
                                            </div>
                                        </div>

                                    </div>

                                @endif

                            @else

                                <a href="{{ route('index') }}"
                                class="btn btn-outline-brand rounded-pill w-100 mt-3">
                                    Ir al panel administrativo
                                </a>

                            @endif
                        @else

                            <a href="{{ route('register.bidder') }}"
                            class="btn btn-brand btn-lg rounded-pill w-100 mt-3">
                                Regístrate para ofertar
                            </a>

                            <a href="{{ route('login') }}"
                            class="btn btn-outline-brand rounded-pill w-100 mt-2">
                                Ya tengo cuenta
                            </a>

                        @endauth
                </div>
            </div>
        </div>
    </div>
</section>
</x-public-layout>
