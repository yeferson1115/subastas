@props(['title' => 'BidFixx | Subastas online inteligentes', 'categories' => collect()])
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-blue: #00305f;
            --brand-blue-2: #005fae;
            --brand-cyan: #06c8d7;
            --brand-cyan-2: #17aee8;
            --brand-slate: #07192f;
            --brand-muted: #64748b;
        }
        body { min-height: 100vh; display: flex; flex-direction: column; background: linear-gradient(180deg, #f6fbff 0%, #ffffff 48%, #eef7fb 100%); color: #172033; }
        main { flex: 1; }
        .fw-black { font-weight: 900; }
        .text-brand-blue { color: var(--brand-blue) !important; }
        .text-brand-cyan { color: var(--brand-cyan) !important; }
        .text-white-75 { color: rgba(255, 255, 255, .78); }
        .site-header { backdrop-filter: blur(14px); background: rgba(255,255,255,.94); border-bottom: 1px solid rgba(0, 48, 95, .09); box-shadow: 0 10px 30px rgba(0, 48, 95, .06); }
        .logo-wrap { width: 210px; max-width: 48vw; display: inline-flex; align-items: center; }
        .logo-wrap img { width: 100%; height: auto; }
        .navbar-nav .nav-link { color: var(--brand-blue); font-weight: 700; }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link:focus { color: var(--brand-cyan-2); }
        .dropdown-menu { border: 1px solid rgba(0, 48, 95, .08); box-shadow: 0 20px 45px rgba(0, 48, 95, .14); border-radius: 18px; }
        .dropdown-item { border-radius: 12px; padding: .65rem .9rem; }
        .dropdown-item:active { background: var(--brand-blue-2); }
        .btn-brand { color: #fff; border: 0; background: linear-gradient(120deg, var(--brand-blue), var(--brand-blue-2), var(--brand-cyan)); box-shadow: 0 14px 28px rgba(0, 95, 174, .25); transition: .2s ease; }
        .btn-brand:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 18px 34px rgba(0, 95, 174, .32); filter: brightness(1.04); }
        .btn-outline-brand { color: var(--brand-blue); border: 1px solid rgba(0, 48, 95, .25); background: rgba(255,255,255,.85); font-weight: 700; }
        .btn-outline-brand:hover { color: #fff; background: var(--brand-blue); border-color: var(--brand-blue); }
        .btn-soft-brand { color: var(--brand-blue); border: 1px solid rgba(6, 200, 215, .35); background: rgba(6, 200, 215, .1); font-weight: 800; }
        .hero-shell { padding: 2rem .75rem 0; }
        .hero-slide { min-height: 540px; padding: clamp(2rem, 5vw, 5rem); display: flex; align-items: center; color: #fff; background-size: cover; background-position: center; }
        .hero-slide-primary { background: radial-gradient(circle at 82% 20%, rgba(6,200,215,.55), transparent 28%), linear-gradient(135deg, rgba(0,48,95,.98), rgba(0,95,174,.88)); }
        .hero-slide-secondary { background: radial-gradient(circle at 20% 18%, rgba(255,255,255,.22), transparent 27%), linear-gradient(135deg, rgba(7,25,47,.97), rgba(6,200,215,.76)); }
        .hero-slide-tertiary { background: radial-gradient(circle at 80% 15%, rgba(6,200,215,.46), transparent 24%), linear-gradient(135deg, rgba(0,48,95,.96), rgba(10,31,68,.92)); }
        .eyebrow, .section-kicker { text-transform: uppercase; letter-spacing: .16rem; font-size: .78rem; font-weight: 900; color: var(--brand-cyan); }
        .hero-metric-card { border-radius: 28px; padding: 2rem; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.28); box-shadow: 0 24px 50px rgba(0,0,0,.18); backdrop-filter: blur(8px); }
        .category-tile { position: relative; min-height: 245px; display: block; overflow: hidden; border-radius: 28px; text-decoration: none; box-shadow: 0 18px 42px rgba(0, 48, 95, .14); background: linear-gradient(135deg, var(--brand-blue), var(--brand-cyan)); }
        .category-tile img { width: 100%; height: 245px; object-fit: cover; transition: transform .35s ease; }
        .category-tile:hover img { transform: scale(1.06); }
        .category-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(7,25,47,.08), rgba(0,48,95,.86)); }
        .category-content { position: absolute; inset: auto 1.35rem 1.35rem; color: #fff; display: flex; flex-direction: column; gap: .15rem; }
        .category-content strong { font-size: 1.35rem; }
        .placeholder-tile { min-height: 245px; }
        .cta-panel { border-radius: 32px; background: radial-gradient(circle at top right, rgba(6,200,215,.48), transparent 28%), linear-gradient(135deg, var(--brand-blue), var(--brand-slate)); box-shadow: 0 22px 50px rgba(0,48,95,.22); }
        .auction-card { height: 100%; border: 0; border-radius: 26px; overflow: hidden; box-shadow: 0 18px 38px rgba(0,48,95,.12); }
        .auction-card-img { height: 210px; object-fit: cover; background: #e2e8f0; }
        .badge-status { background: rgba(6,200,215,.12); color: var(--brand-blue); border: 1px solid rgba(6,200,215,.28); }
        .empty-state { border: 1px dashed rgba(0,48,95,.25); border-radius: 22px; background: #fff; padding: 2rem; text-align: center; color: var(--brand-muted); }
        .form-card { border: 0; border-radius: 28px; box-shadow: 0 22px 55px rgba(0,48,95,.13); overflow: hidden; }
        .form-card-header { background: linear-gradient(135deg, var(--brand-blue), var(--brand-cyan)); color: #fff; }
        .footer { background: var(--brand-slate); color: #e5f5fb; }
        .social-link { width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; background: linear-gradient(130deg, var(--brand-blue-2), var(--brand-cyan)); }
        @media (min-width: 992px) { .dropdown:hover > .dropdown-menu { display: block; margin-top: 0; } }
        @media (max-width: 991.98px) { .hero-slide { min-height: auto; } .navbar-collapse { padding-top: 1rem; } .header-actions { align-items: stretch !important; } }
    </style>
</head>
<body>
    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand logo-wrap" aria-label="Ir a la página principal">
                    <img src="{{ asset('imagenes/logo.jpg') }}" alt="Logo BidFixx">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Abrir menú">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="publicNavbar">
                    <ul class="navbar-nav mx-lg-auto mb-3 mb-lg-0 gap-lg-1">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('public.auctions.index') }}">Subastas</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categorías</a>
                            <ul class="dropdown-menu p-2">
                                @forelse($categories as $category)
                                    <li class="dropend">
                                        <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('public.auctions.index', ['categoria' => $category->slug]) }}">
                                            {{ $category->name }} @if($category->subcategories->isNotEmpty())<i class="bi bi-chevron-right ms-3"></i>@endif
                                        </a>
                                        @if($category->subcategories->isNotEmpty())
                                            <ul class="dropdown-menu p-2">
                                                @foreach($category->subcategories as $subcategory)
                                                    <li><a class="dropdown-item" href="{{ route('public.auctions.index', ['categoria' => $category->slug, 'subcategoria' => $subcategory->slug]) }}">{{ $subcategory->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @empty
                                    <li><span class="dropdown-item text-muted">Sin categorías publicadas</span></li>
                                @endforelse
                            </ul>
                        </li>
                    </ul>
                    <div class="header-actions d-flex flex-column flex-lg-row gap-2 align-items-lg-center">
                        @auth
                            @if(auth()->user()->user_type === \App\Models\User::TYPE_BIDDER || auth()->user()->hasRole(\App\Models\User::TYPE_BIDDER))
                                <span class="small text-muted">Hola, {{ auth()->user()->name }}</span>
                            @else
                                <a href="{{ route('index') }}" class="btn btn-outline-brand rounded-pill px-3">Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-brand rounded-pill px-3" type="submit">Salir</button></form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-brand rounded-pill px-3">Ingresar</a>
                            <a href="{{ route('register.auctioneer') }}" class="btn btn-soft-brand rounded-pill px-3">Subastador</a>
                            <a href="{{ route('register.bidder') }}" class="btn btn-brand rounded-pill px-3">Ofertante</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>{{ $slot }}</main>

    <footer class="footer py-5 mt-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4"><h3 class="h5 fw-bold mb-3">BidFixx</h3><p class="mb-0 text-white-75">Subastas online inteligentes para conectar activos, subastadores y ofertantes en un proceso transparente.</p></div>
                <div class="col-md-4"><h4 class="h6 fw-bold text-uppercase mb-3">Contacto</h4><p class="mb-1"><i class="bi bi-envelope-fill me-2"></i>info@bidfixx.com</p><p class="mb-0"><i class="bi bi-phone-fill me-2"></i>Soporte comercial</p></div>
                <div class="col-md-4"><h4 class="h6 fw-bold text-uppercase mb-3">Síguenos</h4><div class="d-flex gap-2 mb-2"><a href="#" class="social-link" aria-label="Facebook"><i class="bi bi-facebook"></i></a><a href="#" class="social-link" aria-label="Instagram"><i class="bi bi-instagram"></i></a><a href="#" class="social-link" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a><a href="#" class="social-link" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a></div><small class="text-white-75 d-block">© {{ now()->year }} BidFixx. Todos los derechos reservados.</small></div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
