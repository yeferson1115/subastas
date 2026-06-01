@csrf

@if ($client->exists)
    @method('PUT')
@endif

<div class="row">
    <div class="col-12 mb-3">
        <div class="alert alert-info mb-0">
            La información se guarda en la tabla <strong>users</strong> y el registro queda identificado como usuario <strong>subastador</strong>.
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="auctioneer_client_type">Tipo de cliente</label>
            <select class="form-select @error('auctioneer_client_type') is-invalid @enderror" id="auctioneer_client_type" name="auctioneer_client_type" required>
                <option value="natural" @selected(old('auctioneer_client_type', $client->auctioneer_client_type) === 'natural')>Persona natural</option>
                <option value="empresa" @selected(old('auctioneer_client_type', $client->auctioneer_client_type) === 'empresa')>Empresa</option>
            </select>
            @error('auctioneer_client_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="email">Correo electrónico de acceso</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $client->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12">
        <h5 class="mb-3">Datos básicos</h5>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="name">Nombres / Contacto principal</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $client->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12 natural-field">
        <div class="mb-3">
            <label class="form-label" for="last_name">Apellidos</label>
            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $client->last_name) }}">
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="mb-3">
            <label class="form-label" for="document_type">Tipo de documento</label>
            <input type="text" class="form-control @error('document_type') is-invalid @enderror" id="document_type" name="document_type" value="{{ old('document_type', $client->document_type) }}" placeholder="CC, CE, NIT">
            @error('document_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="mb-3">
            <label class="form-label" for="document_number">Número de documento</label>
            <input type="text" class="form-control @error('document_number') is-invalid @enderror" id="document_number" name="document_number" value="{{ old('document_number', $client->document_number) }}">
            @error('document_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="mb-3">
            <label class="form-label" for="phone">Teléfono</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-8 col-12">
        <div class="mb-3">
            <label class="form-label" for="address">Dirección</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $client->address) }}">
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="mb-3">
            <label class="form-label" for="city">Ciudad</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $client->city) }}">
            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12">
        <hr>
        <h5 class="mb-3">Plan del subastador</h5>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="plan_id">Plan asignado</label>
            <select class="form-select @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id">
                <option value="">Sin plan activo</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" data-months="{{ $plan->duration_months }}" @selected((string) old('plan_id', $client->plan_id) === (string) $plan->id)>
                        {{ $plan->name }} - ${{ number_format((float) $plan->price, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3 col-12">
        <div class="mb-3">
            <label class="form-label" for="plan_started_at">Fecha de inicio</label>
            <input type="date" class="form-control @error('plan_started_at') is-invalid @enderror" id="plan_started_at" name="plan_started_at" value="{{ old('plan_started_at', optional($client->plan_started_at)->format('Y-m-d')) }}">
            @error('plan_started_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3 col-12">
        <div class="mb-3">
            <label class="form-label" for="plan_expires_at_preview">Fecha de vencimiento</label>
            <input type="text" class="form-control" id="plan_expires_at_preview" value="{{ optional($client->plan_expires_at)->format('d/m/Y') }}" readonly>
            <small class="text-muted">Se calcula automáticamente según la vigencia.</small>
        </div>
    </div>

    <div class="col-12 company-section">
        <hr>
        <h5 class="mb-3">Datos de la empresa</h5>
    </div>

    <div class="col-md-6 col-12 company-section">
        <div class="mb-3">
            <label class="form-label" for="company_name">Razón social</label>
            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $client->company_name) }}">
            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12 company-section">
        <div class="mb-3">
            <label class="form-label" for="company_document_number">NIT / Identificación tributaria</label>
            <input type="text" class="form-control @error('company_document_number') is-invalid @enderror" id="company_document_number" name="company_document_number" value="{{ old('company_document_number', $client->company_document_number) }}">
            @error('company_document_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12 company-section">
        <div class="mb-3">
            <label class="form-label" for="company_legal_representative">Representante legal</label>
            <input type="text" class="form-control @error('company_legal_representative') is-invalid @enderror" id="company_legal_representative" name="company_legal_representative" value="{{ old('company_legal_representative', $client->company_legal_representative) }}">
            @error('company_legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12 company-section">
        <div class="mb-3">
            <label class="form-label" for="company_phone">Teléfono empresa</label>
            <input type="text" class="form-control @error('company_phone') is-invalid @enderror" id="company_phone" name="company_phone" value="{{ old('company_phone', $client->company_phone) }}">
            @error('company_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12 company-section">
        <div class="mb-3">
            <label class="form-label" for="company_address">Dirección empresa</label>
            <input type="text" class="form-control @error('company_address') is-invalid @enderror" id="company_address" name="company_address" value="{{ old('company_address', $client->company_address) }}">
            @error('company_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-12">
        <hr>
        <h5 class="mb-3">Credenciales</h5>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="password">Contraseña {{ $client->exists ? '(dejar en blanco para conservar)' : '' }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ $client->exists ? '' : 'required' }}>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ $client->exists ? '' : 'required' }}>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fa-solid fa-save"></i> Guardar
    </button>
    <a href="{{ route('admin.auctioneer-clients.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
    function toggleAuctioneerFields() {
        const isCompany = $('#auctioneer_client_type').val() === 'empresa';
        $('.company-section').toggle(isCompany);
        $('#company_name, #company_document_number, #company_legal_representative').prop('required', isCompany);
        $('#last_name').prop('required', !isCompany);
    }

    function updatePlanExpirationPreview() {
        const selected = $('#plan_id option:selected');
        const months = parseInt(selected.data('months'), 10);
        const start = $('#plan_started_at').val();

        $('#plan_started_at').prop('required', Boolean($('#plan_id').val()));

        if (!months || !start) {
            $('#plan_expires_at_preview').val('');
            return;
        }

        const date = new Date(start + 'T00:00:00');
        date.setMonth(date.getMonth() + months);
        $('#plan_expires_at_preview').val(date.toLocaleDateString('es-CO'));
    }

    $(document).ready(function () {
        toggleAuctioneerFields();
        $('#auctioneer_client_type').on('change', toggleAuctioneerFields);
        updatePlanExpirationPreview();
        $('#plan_id, #plan_started_at').on('change', updatePlanExpirationPreview);
    });
</script>
@endpush
