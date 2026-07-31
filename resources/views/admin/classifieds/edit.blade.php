@extends('layouts.app')
@section('title', 'Clasificados')
@section('page_title', 'Clasificados')
@section('page_subtitle', 'Editar')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Editar clasificado</h5>
    <form method="POST" action="{{ route('classifieds.update', $classified) }}" class="card-body" enctype="multipart/form-data">@include('admin.classifieds._form')</form>
</div>
@endsection
