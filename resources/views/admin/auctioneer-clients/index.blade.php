@extends('layouts.app')

@section('title', 'Clientes subastadores')
@section('page_title', 'Clientes subastadores')
@section('page_subtitle', 'Listado')
@section('content')
<div class="content-header row mt-5">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Gestión de clientes subastadores</h2>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
        <a href="{{ route('admin.auctioneer-clients.create') }}" class="btn btn-success waves-effect waves-float waves-light">
            <i class="fa-solid fa-plus"></i> Nuevo cliente
        </a>
    </div>
</div>

<div class="content-body">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Clientes subastadores</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="datatables">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Acciones</th>
                                        <th>Tipo</th>
                                        <th>Nombre / Empresa</th>
                                        <th>Documento / NIT</th>
                                        <th>Contacto</th>
                                        <th>Correo</th>
                                        <th>Plan</th>
                                        <th>Vence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td>{{ $client->id }}</td>
                                            <td class="d-flex gap-1">
                                                <a class="btn btn-warning btn-sm" href="{{ route('admin.auctioneer-clients.edit', $client) }}" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.auctioneer-clients.destroy', $client) }}" class="delete-auctioneer-client-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $client->auctioneer_client_type === 'empresa' ? 'primary' : 'info' }}">
                                                    {{ $client->auctioneer_client_type === 'empresa' ? 'Empresa' : 'Persona natural' }}
                                                </span>
                                            </td>
                                            <td>{{ $client->company_name ?: trim($client->name . ' ' . $client->last_name) }}</td>
                                            <td>{{ $client->company_document_number ?: $client->document_number }}</td>
                                            <td>{{ $client->auctioneer_client_type === 'empresa' ? $client->company_legal_representative : trim($client->name . ' ' . $client->last_name) }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->plan?->name ?? 'Sin plan' }}</td>
                                            <td>
                                                @if ($client->plan_expires_at)
                                                    <span class="badge bg-{{ $client->hasActivePlan() ? 'success' : 'danger' }}">
                                                        {{ $client->plan_expires_at->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Sin vigencia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('submit', '.delete-auctioneer-client-form', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: '¿Eliminar cliente subastador?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
