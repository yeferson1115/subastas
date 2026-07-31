@extends('layouts.app')
@section('title', 'Clasificados')
@section('page_title', 'Clasificados')
@section('page_subtitle', 'Crear')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Nuevo clasificado</h5>
    <form method="POST" action="{{ route('classifieds.store') }}" class="card-body" enctype="multipart/form-data">@include('admin.classifieds._form')</form>
</div>
@endsection
