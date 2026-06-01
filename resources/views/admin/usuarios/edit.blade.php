@extends('layouts.app')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios')
@section('page_subtitle', 'Editar')
@section('content')


<div class="content-header row mt-5">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Editar: {{ Auth::user()->display_name }}</h2>

            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Editar Usuario</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" role="form" id="main-form" autocomplete="off" enctype="multipart/form-data">
                            @method('PUT')
                            <input type="hidden" id="_url" value="{{ url('users',[$user->id]) }}">
                            <input type="hidden" id="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="name">Nombres</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" autocomplete="name" autofocus placeholder="Nombres">
                                        @error('name')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="last_name">Apellidos</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ $user->last_name }}" autocomplete="last_name" autofocus placeholder="Apellidos">
                                        @error('last_name')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                 <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="phone">Teléfono</label>
                                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $user->phone }}" autocomplete="phone" autofocus placeholder="Teléfono">
                                        @error('phone')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="contact">Contacto comercial</label>
                                        <input type="text" id="contact" name="contact" class="form-control" value="{{ $user->contact }}" placeholder="Contacto comercial">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="signature">Firma del vendedor</label>
                                        <input type="file" id="signature" name="signature" class="form-control" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                                        @if ($user->signature_path)
                                            <a href="{{ asset($user->signature_path) }}" target="_blank" class="small d-inline-block mt-1">Ver firma guardada</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="city-column">Género</label>
                                        <div class="demo-inline-spacing">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="M" {{ ($user->gender=="M")? "checked" : "" }} >
                                                <label class="form-check-label" for="inlineRadio1">Masculino</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="F" {{ ($user->gender=="F")? "checked" : "" }}>
                                                <label class="form-check-label" for="inlineRadio2">Femenino</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="email">Correo Electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}"  autocomplete="email" autofocus placeholder="Correo Electrónico">
                                        @error('email')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>





                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="city-column">Tipo de usuario</label>
                                        <div class="demo-inline-spacing">
                                            <input type="hidden" value="{{$roles = Spatie\Permission\Models\Role::get()}}">
                                            @foreach ($roles as $role)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role" id="role{{ $role->id }}" value="{{ $role->id }}" data-user-type="{{ strtolower($role->name) }}" {{ $user->hasRole($role->name) ? "checked" : "" }}>
                                                <label class="form-check-label" for="role{{ $role->id }}">{{ $role->name }}</label>
                                            </div>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 plan-section" style="display:none;">
                                    <hr>
                                    <h5 class="mb-3">Plan del usuario</h5>
                                </div>

                                <div class="col-md-6 col-12 mt-2 plan-section" style="display:none;">
                                    <div class="mb-1">
                                        <label class="form-label" for="plan_id">Plan asignado</label>
                                        <select class="form-select" id="plan_id" name="plan_id">
                                            <option value="">Sin plan activo</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}" data-user-type="{{ $plan->user_type }}" data-months="{{ $plan->duration_months }}" @selected((string) $user->plan_id === (string) $plan->id)>
                                                    {{ $plan->name }} - ${{ number_format((float) $plan->price, 2, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12 mt-2 plan-section" style="display:none;">
                                    <div class="mb-1">
                                        <label class="form-label" for="plan_started_at">Fecha de inicio</label>
                                        <input type="date" class="form-control" id="plan_started_at" name="plan_started_at" value="{{ optional($user->plan_started_at)->format('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12 mt-2 plan-section" style="display:none;">
                                    <div class="mb-1">
                                        <label class="form-label" for="plan_expires_at_preview">Fecha de vencimiento</label>
                                        <input type="text" class="form-control" id="plan_expires_at_preview" value="{{ optional($user->plan_expires_at)->format('d/m/Y') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="password">Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password"  class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}"  autocomplete="password" autofocus placeholder="Contraseña">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-password" style="cursor: pointer;">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="password_confirmation">Confirmar Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password"  class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}"  autocomplete="password_confirmation" placeholder="Confirmar Contraseña">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-password-confirm" style="cursor: pointer;">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback text-center" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                @if(Auth::user()->hasrole('Administrador') && Auth::user()->id != $user->id)
                                <div class="col-md-6 col-12 mt-2">
                                    <div class="mb-1">
                                        <label class="form-label" for="city-column">Acceso al sistema</label>
                                        <div class="demo-inline-spacing">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="status1" value="1" {{ ($user->status=="1")? "checked" : "" }} >
                                                <label class="form-check-label" for="status1">Activo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="status2" value="0" {{ ($user->status=="0")? "checked" : "" }} >
                                                <label class="form-check-label" for="status2">Inactivo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light ajax" id="submit"><i id="ajax-icon" style="margin-right: 10px;" class="fa fa-save"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('scripts')
<script>
        // Toggle password visibility for the 'Nueva Contraseña' field
        document.getElementById('toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');

            // Toggle password visibility
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });

        // Toggle password visibility for the 'Confirmar Nueva Contraseña' field
        document.getElementById('toggle-password-confirm').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('password_confirmation');
            const toggleIconConfirm = document.getElementById('toggle-icon-confirm');

            // Toggle password visibility
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
                toggleIconConfirm.classList.remove('bi-eye-slash');
                toggleIconConfirm.classList.add('bi-eye');
            } else {
                confirmPasswordField.type = 'password';
                toggleIconConfirm.classList.remove('bi-eye');
                toggleIconConfirm.classList.add('bi-eye-slash');
            }
        });
    </script>
    <script src="{{ asset('js/admin/user/edit.js') }}"></script>
<script>
    function selectedUserType() {
        const role = $('input[name="role"]:checked').data('user-type');
        if (role === 'subastador') return 'subastador';
        if (role === 'ofertante') return 'ofertante';
        return '';
    }

    function updateUserPlanOptions() {
        const userType = selectedUserType();
        const needsPlan = ['subastador', 'ofertante'].includes(userType);
        $('.plan-section').toggle(needsPlan);

        $('#plan_id option').each(function () {
            const optionType = $(this).data('user-type');
            $(this).toggle(!optionType || optionType === userType);
        });

        if ($('#plan_id option:selected').is(':hidden')) {
            $('#plan_id').val('');
        }

        updateUserPlanExpirationPreview();
    }

    function updateUserPlanExpirationPreview() {
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
        updateUserPlanOptions();
        $('input[name="role"]').on('change', updateUserPlanOptions);
        $('#plan_id, #plan_started_at').on('change', updateUserPlanExpirationPreview);
    });
</script>
@endpush
