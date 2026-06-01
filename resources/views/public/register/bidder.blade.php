<x-public-layout title="Registro ofertante | BidFixx" :categories="\App\Models\Category::with('subcategories')->orderBy('name')->get()">
<section class="container py-5">
    <div class="row justify-content-center"><div class="col-lg-9"><div class="card form-card"><div class="form-card-header p-4 p-lg-5"><span class="eyebrow text-white-75">Cuenta ofertante</span><h1 class="fw-black mb-2">Regístrate para ofertar</h1><p class="mb-0 text-white-75">Al finalizar quedarás logueado en la parte pública para participar en subastas activas.</p></div><div class="card-body p-4 p-lg-5">
    <form method="POST" action="{{ route('register.bidder.store') }}" class="row g-3">@csrf
        @include('public.register._identity-fields')
        <div class="col-md-6"><label class="form-label fw-bold">Ciudad</label><input name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>@error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label fw-bold">Dirección</label><input name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        @include('public.register._password-fields')
        <div class="col-12"><button class="btn btn-brand btn-lg rounded-pill px-5" type="submit">Crear cuenta ofertante</button></div>
    </form>
</div></div></div></div>
</section>
</x-public-layout>
