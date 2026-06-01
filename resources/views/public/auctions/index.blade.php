<x-public-layout title="Subastas | BidFixx" :categories="$categories">
    <section class="container py-5">
        @if(session('status'))<div class="alert alert-success rounded-4">{{ session('status') }}</div>@endif
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
            <div><span class="section-kicker">Marketplace</span><h1 class="fw-black mb-2">Subastas disponibles</h1><p class="text-muted mb-0">Filtra por categoría y subcategoría para encontrar activos de interés.</p></div>
        </div>
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="card form-card"><div class="card-body p-4"><h2 class="h5 fw-bold mb-3">Categorías</h2><a class="d-block text-decoration-none mb-2" href="{{ route('public.auctions.index') }}">Todas</a>@foreach($categories as $category)<div class="mb-3"><a class="fw-bold text-brand-blue text-decoration-none" href="{{ route('public.auctions.index', ['categoria' => $category->slug]) }}">{{ $category->name }}</a>@foreach($category->subcategories as $subcategory)<a class="d-block small text-muted text-decoration-none mt-1" href="{{ route('public.auctions.index', ['categoria' => $category->slug, 'subcategoria' => $subcategory->slug]) }}">{{ $subcategory->name }}</a>@endforeach</div>@endforeach</div></div>
            </aside>
            <div class="col-lg-9"><div class="row g-4">@forelse($auctions as $auction)<div class="col-md-6 col-xl-4">@include('public.auctions._card', ['auction' => $auction])</div>@empty<div class="col-12"><div class="empty-state">No hay subastas para este filtro.</div></div>@endforelse</div><div class="mt-4">{{ $auctions->links() }}</div></div>
        </div>
    </section>
</x-public-layout>
