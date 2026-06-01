@extends('layouts.app')

@section('title', 'Planes')
@section('page_title', 'Planes')
@section('page_subtitle', 'Configuración')
@section('content')
<div class="content-header row mt-5">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Configuración de planes</h2>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.plans.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            @foreach ($userTypeLabels as $userType => $label)
                @php($plan = $plans->get($userType))
                <div class="col-lg-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Plan para {{ strtolower($label) }}</h4>
                            <small class="text-muted">Solo existe un plan para este tipo de usuario. Configure su valor y vigencia.</small>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="plans[{{ $plan->id }}][id]" value="{{ $plan->id }}">

                            <div class="mb-3">
                                <label class="form-label" for="duration_months_{{ $plan->id }}">Vigencia</label>
                                <select class="form-select @error('plans.' . $plan->id . '.duration_months') is-invalid @enderror" id="duration_months_{{ $plan->id }}" name="plans[{{ $plan->id }}][duration_months]" required>
                                    @foreach ($durations as $months => $labelDuration)
                                        <option value="{{ $months }}" @selected((int) old('plans.' . $plan->id . '.duration_months', $plan->duration_months) === (int) $months)>
                                            {{ $labelDuration }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plans.' . $plan->id . '.duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="price_{{ $plan->id }}">Valor del plan</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('plans.' . $plan->id . '.price') is-invalid @enderror" id="price_{{ $plan->id }}" name="plans[{{ $plan->id }}][price]" value="{{ old('plans.' . $plan->id . '.price', $plan->price) }}" required>
                                @error('plans.' . $plan->id . '.price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-check form-switch">
                                <input type="hidden" name="plans[{{ $plan->id }}][is_active]" value="0">
                                <input class="form-check-input" type="checkbox" id="is_active_{{ $plan->id }}" name="plans[{{ $plan->id }}][is_active]" value="1" @checked(old('plans.' . $plan->id . '.is_active', $plan->is_active))>
                                <label class="form-check-label" for="is_active_{{ $plan->id }}">Plan activo</label>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-save"></i> Guardar planes
        </button>
    </form>
</div>
@endsection
