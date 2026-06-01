@extends('layouts.app')
@section('title', 'Productos a subastar')
@section('page_title', 'Productos a subastar')
@section('page_subtitle', 'Editar')
@section('content')
<div class="card mb-6">
    <h5 class="card-header">Editar producto a subastar</h5>
    <form method="POST" action="{{ route('auction-products.update', $product) }}" class="card-body auction-product-form" enctype="multipart/form-data">
        @include('admin.auction-products._form')
    </form>
</div>
@endsection
