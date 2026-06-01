@extends('layouts.app')
@section('title', 'Subcategorías')
@section('page_title', 'Subcategorías')
@section('page_subtitle', 'Editar')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Editar Subcategoría</h5>
    <form method="POST" action="{{ route('subcategories.update', $subcategory) }}" class="card-body" enctype="multipart/form-data">
        @include('admin.subcategories._form')
    </form>
</div>
@endsection
