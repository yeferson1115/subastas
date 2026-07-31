<x-public-layout title="{{ $classified->title }} | Clasificados BidFixx" :categories="$categories">
    <section class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.classifieds.index') }}" class="text-decoration-none">Clasificados</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $classified->title }}</li>
            </ol>
        </nav>

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="card form-card overflow-hidden">
                    @if(! empty($classified->images))
                        <div id="classifiedGallery" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner bg-light">
                                @foreach($classified->images as $image)
                                    <div class="carousel-item @if($loop->first) active @endif">
                                        <img src="{{ asset($image) }}" class="d-block w-100" alt="{{ $classified->title }} - imagen {{ $loop->iteration }}" style="height: 520px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($classified->images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#classifiedGallery" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#classifiedGallery" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Siguiente</span>
                                </button>
                            @endif
                        </div>
                        @if(count($classified->images) > 1)
                            <div class="p-3 bg-white border-top">
                                <div class="row g-2">
                                    @foreach($classified->images as $image)
                                        <div class="col-4 col-md-3">
                                            <button type="button" class="border-0 p-0 bg-transparent w-100" data-bs-target="#classifiedGallery" data-bs-slide-to="{{ $loop->index }}" aria-label="Ver imagen {{ $loop->iteration }}">
                                                <img src="{{ asset($image) }}" alt="Miniatura {{ $loop->iteration }} de {{ $classified->title }}" class="rounded w-100" style="height: 92px; object-fit: cover;">
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 420px;">Sin imágenes disponibles</div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card form-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge badge-status mb-3">{{ $classified->category?->name }}@if($classified->subcategory) / {{ $classified->subcategory->name }}@endif</span>
                        <h1 class="fw-black mb-3">{{ $classified->title }}</h1>
                        <div class="display-6 fw-black text-brand-blue mb-4">${{ number_format((float) $classified->price, 0, ',', '.') }}</div>

                        <div class="d-grid gap-2 mb-4">
                            @if($classified->whatsapp_url)
                                <a class="btn btn-success btn-lg rounded-pill" href="{{ $classified->whatsapp_url }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-2"></i> Contactar por WhatsApp</a>
                            @else
                                <a class="btn btn-brand btn-lg rounded-pill" href="mailto:{{ $classified->contact }}"><i class="bi bi-envelope me-2"></i> Contactar</a>
                            @endif
                        </div>

                        <div class="border-top pt-4">
                            <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Información de contacto</h2>
                            <p class="mb-1"><i class="bi bi-person-circle me-2 text-brand-blue"></i>{{ trim(($classified->user?->name ?? '') . ' ' . ($classified->user?->last_name ?? '')) ?: 'Usuario BidFixx' }}</p>
                            <p class="mb-0"><i class="bi bi-telephone me-2 text-brand-blue"></i>{{ $classified->contact }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('public.classifieds.index', request()->only(['categoria', 'subcategoria', 'precio_min', 'precio_max'])) }}" class="btn btn-outline-brand rounded-pill"><i class="bi bi-arrow-left me-1"></i> Volver a clasificados</a>
            </div>
        </div>

        <div class="card form-card mt-4">
            <div class="card-body p-4 p-lg-5">
                <span class="section-kicker">Descripción</span>
                <h2 class="fw-black mb-3">Detalles del clasificado</h2>
                <div class="text-muted fs-5" style="white-space: pre-line;">{{ $classified->description }}</div>
            </div>
        </div>
    </section>
</x-public-layout>
