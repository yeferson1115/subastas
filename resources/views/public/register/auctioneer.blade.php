<x-public-layout title="Registro subastador | BidFixx" :categories="\App\Models\Category::with('subcategories')->orderBy('name')->get()">
<section class="container py-5">
    <div class="row justify-content-center"><div class="col-lg-10"><div class="card form-card"><div class="form-card-header p-4 p-lg-5"><span class="eyebrow text-white-75">Cuenta subastador</span><h1 class="fw-black mb-2">Publica activos en BidFixx</h1><p class="mb-0 text-white-75">Al terminar, ingresarás al panel administrativo para gestionar productos, subastas y ofertas.</p></div><div class="card-body p-4 p-lg-5">
    <form method="POST" action="{{ route('register.auctioneer.store') }}" class="row g-3">@csrf
        @include('public.register._identity-fields')
        <div class="col-md-4"><label class="form-label fw-bold">Tipo de subastador</label><select name="auctioneer_client_type" class="form-select @error('auctioneer_client_type') is-invalid @enderror" required><option value="natural" @selected(old('auctioneer_client_type') === 'natural')>Persona natural</option><option value="empresa" @selected(old('auctioneer_client_type') === 'empresa')>Empresa</option></select>@error('auctioneer_client_type')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-4"><label class="form-label fw-bold">Ciudad</label><input name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>@error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-4"><label class="form-label fw-bold">Dirección</label><input name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12"><hr><h2 class="h5 fw-bold text-brand-blue">Datos de empresa (si aplica)</h2></div>
        <div class="col-md-6"><label class="form-label">Razón social</label><input name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}">@error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label">NIT / documento empresa</label><input name="company_document_number" class="form-control @error('company_document_number') is-invalid @enderror" value="{{ old('company_document_number') }}">@error('company_document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-4"><label class="form-label">Representante legal</label><input name="company_legal_representative" class="form-control" value="{{ old('company_legal_representative') }}"></div>
        <div class="col-md-4"><label class="form-label">Teléfono empresa</label><input name="company_phone" class="form-control" value="{{ old('company_phone') }}"></div>
        <div class="col-md-4"><label class="form-label">Dirección empresa</label><input name="company_address" class="form-control" value="{{ old('company_address') }}"></div>
        @include('public.register._password-fields')
        <div class="col-12"><button class="btn btn-brand btn-lg rounded-pill px-5" type="submit">Crear cuenta subastador</button></div>
    </form>
</div></div></div></div>
</section>
</x-public-layout>
