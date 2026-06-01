@extends('layouts.app')

@section('title', 'Clientes subastadores')
@section('page_title', 'Clientes subastadores')
@section('page_subtitle', 'Editar')
@section('content')
<div class="content-header row mt-5">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Editar cliente subastador</h2>
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
                        <h4 class="card-title">{{ $client->company_name ?: trim($client->name . ' ' . $client->last_name) }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.auctioneer-clients.update', $client) }}" autocomplete="off">
                            @include('admin.auctioneer-clients._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
