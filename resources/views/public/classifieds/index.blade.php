<x-public-layout title="Clasificados | BidFixx" :categories="$categories">
    <section class="container py-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
            <div><span class="section-kicker">Marketplace</span><h1 class="fw-black mb-2">Clasificados disponibles</h1><p class="text-muted mb-0">Encuentra productos con filtros por categoría, subcategoría y precio.</p></div>
        </div>
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="card form-card"><div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Filtros</h2>
                    <form method="GET" action="{{ route('public.classifieds.index') }}" class="vstack gap-3">
                        <div><label class="form-label">Categoría</label><select name="categoria" class="form-select" onchange="this.form.submit()"><option value="">Todas</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
                        <div><label class="form-label">Subcategoría</label><select name="subcategoria" class="form-select"><option value="">Todas</option>@foreach($categories as $category)@foreach($category->subcategories as $subcategory)<option value="{{ $subcategory->slug }}" @selected($selectedSubcategory === $subcategory->slug)>{{ $category->name }} / {{ $subcategory->name }}</option>@endforeach @endforeach</select></div>
                        <div><label class="form-label">Precio mínimo</label><input name="precio_min" type="number" min="0" step="0.01" class="form-control" value="{{ request('precio_min') }}"></div>
                        <div><label class="form-label">Precio máximo</label><input name="precio_max" type="number" min="0" step="0.01" class="form-control" value="{{ request('precio_max') }}"></div>
                        <button class="btn btn-brand rounded-pill" type="submit">Filtrar</button><a class="btn btn-outline-brand rounded-pill" href="{{ route('public.classifieds.index') }}">Limpiar</a>
                    </form>
                </div></div>
            </aside>
            <div class="col-lg-9"><div class="row g-4">
                @forelse($classifieds as $classified)
                    <div class="col-md-6 col-xl-4">
                        <div class="card auction-card">
                            @if(! empty($classified->images[0]))<img src="{{ asset($classified->images[0]) }}" class="auction-card-img" alt="{{ $classified->title }}">@else<div class="auction-card-img d-flex align-items-center justify-content-center text-muted">Sin imagen</div>@endif
                            <div class="card-body d-flex flex-column">
                                <span class="badge badge-status align-self-start mb-2">{{ $classified->category?->name }}@if($classified->subcategory) / {{ $classified->subcategory->name }}@endif</span>
                                <h2 class="h5 fw-black">{{ $classified->title }}</h2>
                                <p class="text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($classified->description), 120) }}</p>
                                <div class="fw-black text-brand-blue fs-5 mb-3">${{ number_format((float) $classified->price, 0, ',', '.') }}</div>
                                @if($classified->whatsapp_url)<a class="btn btn-success rounded-pill" href="{{ $classified->whatsapp_url }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-1"></i> WhatsApp</a>@else<a class="btn btn-outline-brand rounded-pill" href="mailto:{{ $classified->contact }}">Contactar</a>@endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="empty-state">No hay clasificados para este filtro.</div></div>
                @endforelse
            </div><div class="mt-4">{{ $classifieds->links() }}</div></div>
        </div>
    </section>
</x-public-layout>
