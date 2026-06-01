@csrf
@if($product->exists)
    @method('PUT')
@endif

@php
    $details = old('product_details', $product->product_details ?? []);
    $selectedType = old('product_type', $product->product_type);
    $selectedCategory = old('category_id', $product->category_id);
    $selectedSubcategory = old('subcategory_id', $product->subcategory_id);
@endphp

<div class="row g-6">
    @if($isAdmin)
        <div class="col-md-6">
            <label class="form-label" for="auctioneer_id">Subastador responsable</label>
            <select id="auctioneer_id" name="auctioneer_id" class="form-select @error('auctioneer_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach($auctioneers as $auctioneer)
                    <option value="{{ $auctioneer->id }}" @selected(old('auctioneer_id', $product->auctioneer_id) == $auctioneer->id)>
                        {{ $auctioneer->company_name ?: trim($auctioneer->name . ' ' . $auctioneer->last_name) }} - {{ $auctioneer->email }}
                    </option>
                @endforeach
            </select>
            @error('auctioneer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @else
        <input type="hidden" name="auctioneer_id" value="{{ auth()->id() }}">
        <div class="col-md-6">
            <label class="form-label">Subastador responsable</label>
            <input class="form-control" value="{{ auth()->user()->company_name ?: auth()->user()->name }}" disabled>
        </div>
    @endif

    <div class="col-md-6">
        <label class="form-label" for="name">Nombre del producto</label>
        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label" for="auction_start_date">Fecha de inicio</label>
        <input id="auction_start_date" name="auction_start_date" type="date" class="form-control @error('auction_start_date') is-invalid @enderror" value="{{ old('auction_start_date', optional($product->auction_start_date)->format('Y-m-d')) }}" required>
        @error('auction_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="auction_start_time">Hora de inicio</label>
        <input id="auction_start_time" name="auction_start_time" type="time" class="form-control @error('auction_start_time') is-invalid @enderror" value="{{ old('auction_start_time', $product->auction_start_time ? substr($product->auction_start_time, 0, 5) : '') }}" required>
        @error('auction_start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="auction_end_date">Fecha de fin</label>
        <input id="auction_end_date" name="auction_end_date" type="date" class="form-control @error('auction_end_date') is-invalid @enderror" value="{{ old('auction_end_date', optional($product->auction_end_date)->format('Y-m-d')) }}" required>
        @error('auction_end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="auction_end_time">Hora de fin</label>
        <input id="auction_end_time" name="auction_end_time" type="time" class="form-control @error('auction_end_time') is-invalid @enderror" value="{{ old('auction_end_time', $product->auction_end_time ? substr($product->auction_end_time, 0, 5) : '') }}" required>
        @error('auction_end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="base_price">Precio base</label>
        <input id="base_price" name="base_price" type="number" min="0" step="0.01" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $product->base_price) }}" required>
        @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="category_id">Categoría</label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required data-subcategories-url-template="{{ url('categories') }}/:id/subcategories/json">
            <option value="">Seleccione...</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="subcategory_id">Subcategoría</label>
        <select id="subcategory_id" name="subcategory_id" class="form-select @error('subcategory_id') is-invalid @enderror" data-selected="{{ $selectedSubcategory }}">
            <option value="">Seleccione una categoría primero...</option>
            @foreach($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}" @selected($selectedSubcategory == $subcategory->id)>{{ $subcategory->name }}</option>
            @endforeach
        </select>
        @error('subcategory_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="product_type">Tipo de producto</label>
        <select id="product_type" name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($productTypes as $type)
                <option value="{{ $type }}" @selected($selectedType === $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="location">Ubicación</label>
        <input id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $product->location) }}" required>
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="contact_phone">Teléfono de contacto</label>
        <input id="contact_phone" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $product->contact_phone) }}" required>
        @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label" for="mandatory_visit">Visita obligatoria</label>
        <select id="mandatory_visit" name="mandatory_visit" class="form-select @error('mandatory_visit') is-invalid @enderror" required>
            <option value="0" @selected(old('mandatory_visit', (int) $product->mandatory_visit) == 0)>No</option>
            <option value="1" @selected(old('mandatory_visit', (int) $product->mandatory_visit) == 1)>Sí</option>
        </select>
        @error('mandatory_visit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="quantity">Cantidad</label>
        <input id="quantity" name="quantity" type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity ?? 1) }}" required>
        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="technical_sheet">Ficha técnica</label>
        <input id="technical_sheet" name="technical_sheet" type="file" class="form-control @error('technical_sheet') is-invalid @enderror">
        @if($product->technical_sheet_path)<div class="form-text"><a href="{{ asset($product->technical_sheet_path) }}" target="_blank">Ver archivo actual</a></div>@endif
        @error('technical_sheet')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="terms">Términos y condiciones</label>
        <input id="terms" name="terms" type="file" class="form-control @error('terms') is-invalid @enderror">
        @if($product->terms_path)<div class="form-text"><a href="{{ asset($product->terms_path) }}" target="_blank">Ver archivo actual</a></div>@endif
        @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="card mt-6">
    <div class="card-header"><h6 class="mb-0">Datos relevantes según el tipo de producto</h6></div>
    <div class="card-body row g-6 product-type-fields">
        @foreach(['placa' => 'Placa', 'marca' => 'Marca', 'linea' => 'Línea', 'modelo' => 'Modelo', 'servicio' => 'Servicio', 'kilometraje' => 'Kilometraje', 'combustible' => 'Tipo de combustible', 'cilindraje' => 'Cilindraje', 'area' => 'Área', 'matricula' => 'Matrícula inmobiliaria', 'material' => 'Material', 'estado' => 'Estado', 'serie' => 'Serie / referencia'] as $field => $label)
            <div class="col-md-4 type-field" data-types="{{ in_array($field, ['area','matricula']) ? 'Propiedad raiz' : (in_array($field, ['material']) ? 'Chatarra,mobiliario' : (in_array($field, ['serie']) ? 'Maquinaria,mobiliario' : 'Vehiculo,Moto,Maquinaria')) }}">
                <label class="form-label" for="detail_{{ $field }}">{{ $label }}</label>
                <input id="detail_{{ $field }}" name="product_details[{{ $field }}]" class="form-control" value="{{ $details[$field] ?? '' }}">
            </div>
        @endforeach
    </div>
</div>

<div class="row g-6 mt-1">
    <div class="col-12">
        <label class="form-label" for="detail">Detalle</label>
        <textarea id="detail" name="detail" class="form-control @error('detail') is-invalid @enderror" rows="8" required>{{ old('detail', $product->detail) }}</textarea>
        @error('detail')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="images">Imágenes</label>
        <input id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="form-control @error('images') is-invalid @enderror" @required(! $product->exists)>
        <div class="form-text">{{ $product->exists ? 'Si cargas nuevas imágenes, reemplazarán las actuales.' : 'Puedes seleccionar varias imágenes.' }}</div>
        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @error('images.*')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

@if(! empty($product->images))
    <div class="d-flex gap-2 flex-wrap mt-4">
        @foreach($product->images as $image)
            <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="rounded" width="100" height="100" style="object-fit: cover;">
        @endforeach
    </div>
@endif

<div class="pt-6 d-flex gap-2">
    <button class="btn btn-primary mb-5">{{ $product->exists ? 'Actualizar' : 'Guardar' }}</button>
    <a href="{{ route('auction-products.index') }}" class="btn btn-outline-secondary mb-5">Cancelar</a>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}">
<style>.ql-editor{min-height:180px}</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const category = document.getElementById('category_id');
        const subcategory = document.getElementById('subcategory_id');
        const productType = document.getElementById('product_type');

        function loadSubcategories() {
            const selected = subcategory.dataset.selected || subcategory.value;
            subcategory.innerHTML = '<option value="">Cargando...</option>';
            if (!category.value) {
                subcategory.innerHTML = '<option value="">Seleccione una categoría primero...</option>';
                return;
            }
            fetch(category.dataset.subcategoriesUrlTemplate.replace(':id', category.value))
                .then(response => response.json())
                .then(items => {
                    subcategory.innerHTML = '<option value="">Seleccione...</option>';
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        option.selected = String(item.id) === String(selected);
                        subcategory.appendChild(option);
                    });
                    subcategory.dataset.selected = '';
                });
        }

        function toggleTypeFields() {
            const type = productType.value;
            document.querySelectorAll('.type-field').forEach(field => {
                const show = field.dataset.types.split(',').includes(type);
                field.classList.toggle('d-none', !show);
                const input = field.querySelector('input');
                if (input) input.disabled = !show;
            });
        }

        category.addEventListener('change', loadSubcategories);
        productType.addEventListener('change', toggleTypeFields);
        if (category.value) loadSubcategories();
        toggleTypeFields();

        if (window.Quill) {
            const textarea = document.getElementById('detail');
            const editor = document.createElement('div');
            editor.innerHTML = textarea.value;
            textarea.classList.add('d-none');
            textarea.removeAttribute('required');
            textarea.parentNode.insertBefore(editor, textarea.nextSibling);
            const quill = new Quill(editor, { theme: 'snow' });
            textarea.closest('form').addEventListener('submit', () => {
                textarea.value = quill.root.innerHTML;
            });
        }
    });
</script>
@endpush
