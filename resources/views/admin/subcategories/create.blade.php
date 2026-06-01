@extends('layouts.app')
@section('title', 'Subcategorías')
@section('page_title', 'Subcategorías')
@section('page_subtitle', 'Crear')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Nueva Subcategoría</h5>
    <form method="POST" action="{{ route('subcategories.store') }}" class="card-body" enctype="multipart/form-data">
        @include('admin.subcategories._form')
    </form>
</div>
@endsection
