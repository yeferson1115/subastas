@csrf
@if($subcategory->exists)
    @method('PUT')
@endif

<div class="row g-6">
    <div class="col-md-6">
        <label class="form-label" for="category_id">Categoría</label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $subcategory->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="name">Nombre</label>
        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $subcategory->name) }}" required>
        <div class="form-text">El slug se genera automáticamente.</div>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="image">Imagen</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror" @required(! $subcategory->exists)>
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

@if ($subcategory->image)
    <div class="pt-6">
        <p class="mb-2 fw-semibold">Imagen actual</p>
        <img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->name }}" class="rounded" width="120" height="120" style="object-fit: cover;">
    </div>
@endif

<div class="pt-6 d-flex gap-2">
    <button class="btn btn-primary mb-5">{{ $subcategory->exists ? 'Actualizar' : 'Guardar' }}</button>
    <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary mb-5">Cancelar</a>
</div>
