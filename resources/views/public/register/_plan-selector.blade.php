@php
    $selectedPlanId = (string) old('plan_id', optional($plans->first())->id);
@endphp

<div class="col-12">
    <div class="plan-selector p-3 p-lg-4 rounded-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
            <div>
                <span class="section-kicker">Elige tu plan</span>
                <h2 class="h4 fw-black mb-1">Selecciona el plan para activar tu cuenta</h2>
                <p class="text-muted mb-0">La pasarela de pagos se implementará más adelante; por ahora tu cuenta queda asignada al plan elegido.</p>
            </div>
        </div>
        <div class="row g-3">
            @forelse($plans as $plan)
                <div class="col-md-4">
                    <input class="btn-check @error('plan_id') is-invalid @enderror" type="radio" name="plan_id" id="plan_{{ $plan->id }}" value="{{ $plan->id }}" @checked($selectedPlanId === (string) $plan->id) required>
                    <label class="plan-card h-100" for="plan_{{ $plan->id }}">
                        <span class="plan-card-badge">{{ $plan->durationLabel() }}</span>
                        <strong class="d-block fs-5 mt-2">{{ $plan->name }}</strong>
                        <span class="plan-price d-block my-2">${{ number_format((float) $plan->price, 0, ',', '.') }}</span>
                        <small class="text-muted">Activación inmediata al finalizar el registro.</small>
                    </label>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">No hay planes activos para este tipo de cuenta. Contacta al administrador.</div>
                </div>
            @endforelse
        </div>
        @error('plan_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    </div>
</div>
