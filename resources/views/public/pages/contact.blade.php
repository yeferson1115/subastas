<x-public-layout title="BidFixx | Contacto" :categories="$categories ?? collect()">
    <section class="container py-5">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="cta-panel h-100 p-4 p-lg-5 text-white">
                    <span class="section-kicker text-white-50">Contacto</span>
                    <h1 class="fw-black mt-3 mb-3">Hablemos de tus subastas y oportunidades.</h1>
                    <p class="text-white-75 mb-4">Nuestro equipo comercial y de soporte está listo para ayudarte con registros, publicación de lotes, ofertas y dudas generales de BidFixx.</p>
                    <div class="d-flex flex-column gap-3">
                        <div><i class="bi bi-envelope-fill me-2"></i>info@bidfixx.com</div>
                        <div><i class="bi bi-phone-fill me-2"></i>Soporte comercial</div>
                        <div><i class="bi bi-clock-fill me-2"></i>Lunes a viernes, horario laboral</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card form-card h-100">
                    <div class="form-card-header p-4">
                        <h2 class="h4 fw-bold mb-1">Formulario de contacto</h2>
                        <p class="mb-0 text-white-75">Déjanos tus datos y guardaremos tu solicitud para darle seguimiento.</p>
                    </div>
                    <div class="card-body p-4">
                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        <form method="POST" action="{{ route('public.contact.store') }}" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label" for="name">Nombre completo</label>
                                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Teléfono</label>
                                <input id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="subject">Asunto</label>
                                <input id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="message">Mensaje</label>
                                <textarea id="message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button class="btn btn-brand rounded-pill px-4" type="submit">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
