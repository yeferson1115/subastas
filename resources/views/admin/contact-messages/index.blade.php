@extends('layouts.app')

@section('title', 'Mensajes recibidos')
@section('page_title', 'Mensajes recibidos')
@section('page_subtitle', 'Consultas enviadas desde el formulario de contacto')
@section('content')
<div class="card mb-6">
    <div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3 mb-3">
        <div class="col-md-auto me-auto">
            <h5 class="card-title mb-0">Mensajes recibidos</h5>
            <small class="text-muted">Listado de mensajes enviados por visitantes desde la página de contacto.</small>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr>
                            <td>{{ $message->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $message->name }}</td>
                            <td>
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                @if($message->phone)
                                    <br><small class="text-muted">{{ $message->phone }}</small>
                                @endif
                            </td>
                            <td>{{ $message->subject }}</td>
                            <td style="min-width: 280px; white-space: pre-line;">{{ $message->message }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay mensajes recibidos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    </div>
</div>
@endsection
