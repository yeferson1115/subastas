<x-public-layout title="BidFixx | Subastas online inteligentes" :categories="$categories ?? collect()">
    @php
        $featuredCategories = ($categories ?? collect())->take(6);
        $auctionCards = ($featuredAuctions ?? collect())->take(6);
    @endphp

    <section class="hero-shell">
        <div id="homeAuctionCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5200">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#homeAuctionCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Subastas inteligentes"></button>
                <button type="button" data-bs-target="#homeAuctionCarousel" data-bs-slide-to="1" aria-label="Vende como subastador"></button>
                <button type="button" data-bs-target="#homeAuctionCarousel" data-bs-slide-to="2" aria-label="Ofertas seguras"></button>
            </div>
            <div class="carousel-inner rounded-5 overflow-hidden">
                <div class="carousel-item active">
                    <div class="hero-slide hero-slide-primary">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-7">
                                <span class="eyebrow">Subastas online inteligentes</span>
                                <h2 class="display-4 fw-black mt-3 mb-3">Compra y vende activos en subastas confiables, ágiles y transparentes.</h2>
                                <p class="lead mb-4">BidFixx conecta subastadores profesionales con ofertantes verificados para competir en tiempo real por vehículos, maquinaria, inmuebles, mobiliario y más.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('public.auctions.index') }}" class="btn btn-brand btn-lg rounded-pill px-4">Ver subastas</a>
                                    <a href="{{ route('register.bidder') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">Registrarme para ofertar</a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="hero-metric-card">
                                    <i class="bi bi-hammer display-3 text-brand-cyan"></i>
                                    <h3 class="h2 fw-bold mt-3">Ofertas en vivo</h3>
                                    <p class="mb-0 text-white-75">Consulta fechas, precio base, ficha técnica y condiciones antes de pujar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide hero-slide-secondary">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-7">
                                <span class="eyebrow">Para subastadores</span>
                                <h2 class="display-5 fw-black mt-3 mb-3">Publica lotes, administra pujas y recibe compradores calificados.</h2>
                                <p class="lead mb-4">Crea tu cuenta de subastador y accede al panel administrativo para cargar productos, documentos, imágenes y condiciones de venta.</p>
                                <a href="{{ route('register.auctioneer') }}" class="btn btn-brand btn-lg rounded-pill px-4">Ser subastador</a>
                            </div>
                            <div class="col-lg-5">
                                <div class="hero-metric-card bg-white text-dark">
                                    <i class="bi bi-kanban display-3 text-brand-blue"></i>
                                    <h3 class="h2 fw-bold mt-3">Panel profesional</h3>
                                    <p class="mb-0 text-muted">Control total del inventario, calendario de subastas y seguimiento a ofertas.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide hero-slide-tertiary">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-7">
                                <span class="eyebrow">Para ofertantes</span>
                                <h2 class="display-5 fw-black mt-3 mb-3">Regístrate, permanece en la zona pública y oferta sin fricción.</h2>
                                <p class="lead mb-4">Tu cuenta de ofertante queda lista para explorar categorías, revisar subastas activas y enviar ofertas desde la ficha pública del lote.</p>
                                <a href="{{ route('register.bidder') }}" class="btn btn-light btn-lg rounded-pill px-4 text-brand-blue fw-bold">Crear cuenta ofertante</a>
                            </div>
                            <div class="col-lg-5">
                                <div class="hero-metric-card">
                                    <i class="bi bi-shield-check display-3 text-brand-cyan"></i>
                                    <h3 class="h2 fw-bold mt-3">Proceso seguro</h3>
                                    <p class="mb-0 text-white-75">Usuarios identificados, historial de pujas y reglas claras para cada subasta.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeAuctionCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeAuctionCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Siguiente</span></button>
        </div>
    </section>

    <section class="container py-5" id="categorias">
        <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4">
            <div>
                <span class="section-kicker">Explora por categoría</span>
                <h2 class="fw-black mb-2">Encuentra oportunidades por tipo de activo</h2>
                <p class="text-muted mb-0">Las categorías muestran sus subcategorías en el menú y aquí se presentan con imagen para navegación rápida.</p>
            </div>
            <a href="{{ route('public.auctions.index') }}" class="btn btn-soft-brand rounded-pill px-4">Ver todas las subastas</a>
        </div>
        <div class="row g-4">
            @forelse($featuredCategories as $category)
                <div class="col-md-6 col-xl-4">
                    <a href="{{ route('public.auctions.index', ['categoria' => $category->slug]) }}" class="category-tile">
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" onerror="this.src='{{ asset('images/auction-products/1275010a-5b99-4302-8a1a-1ad3d49868ad.png') }}'">
                        <span class="category-overlay"></span>
                        <span class="category-content">
                            <strong>{{ $category->name }}</strong>
                            <small>{{ $category->subcategories->count() }} subcategorías</small>
                        </span>
                    </a>
                </div>
            @empty
                @foreach(['Vehículos', 'Maquinaria', 'Inmuebles'] as $placeholder)
                    <div class="col-md-6 col-xl-4">
                        <div class="category-tile placeholder-tile">
                            <span class="category-overlay"></span>
                            <span class="category-content"><strong>{{ $placeholder }}</strong><small>Configura categorías en el panel</small></span>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>

    <section class="container pb-5">
        <div class="cta-panel p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <span class="section-kicker text-white-50">Únete a BidFixx</span>
                    <h2 class="fw-black text-white mb-3">Dos caminos, una plataforma de subastas profesional.</h2>
                    <p class="text-white-75 mb-0">Si vendes activos, regístrate como subastador y entrarás al panel administrativo. Si compras, crea tu cuenta de ofertante y permanece en la experiencia pública para ofertar.</p>
                </div>
                <div class="col-lg-5 d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                    <a href="{{ route('register.auctioneer') }}" class="btn btn-light btn-lg rounded-pill px-4 text-brand-blue fw-bold">Ser subastador</a>
                    <a href="{{ route('register.bidder') }}" class="btn btn-brand btn-lg rounded-pill px-4">Ser ofertante</a>
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="section-kicker">Subastas destacadas</span>
                <h2 class="fw-black mb-0">Lotes recientes</h2>
            </div>
            <a href="{{ route('public.auctions.index') }}" class="btn btn-outline-brand rounded-pill">Ver más</a>
        </div>
        <div class="row g-4">
            @forelse($auctionCards as $auction)
                <div class="col-md-6 col-xl-4">
                    @include('public.auctions._card', ['auction' => $auction])
                </div>
            @empty
                <div class="col-12"><div class="empty-state">Aún no hay subastas publicadas. Crea productos desde el panel administrativo.</div></div>
            @endforelse
        </div>
    </section>
</x-public-layout>
