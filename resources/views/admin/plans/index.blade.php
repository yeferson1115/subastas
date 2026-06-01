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
                <div class="col-lg-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4 class="card-title mb-0">{{ $label }}</h4>
                            <small class="text-muted">Configure el valor para vigencias de 1 mes, 6 meses y 1 año.</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Vigencia</th>
                                            <th>Valor</th>
                                            <th>Activo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($plans->get($userType, collect()) as $plan)
                                            <tr>
                                                <td>
                                                    {{ $plan->durationLabel() }}
                                                    <input type="hidden" name="plans[{{ $plan->id }}][id]" value="{{ $plan->id }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0" class="form-control @error('plans.' . $plan->id . '.price') is-invalid @enderror" name="plans[{{ $plan->id }}][price]" value="{{ old('plans.' . $plan->id . '.price', $plan->price) }}" required>
                                                    @error('plans.' . $plan->id . '.price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="plans[{{ $plan->id }}][is_active]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="plans[{{ $plan->id }}][is_active]" value="1" @checked(old('plans.' . $plan->id . '.is_active', $plan->is_active))>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
