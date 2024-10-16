<variant-list class="variants-container d-flex flex-column gap-3 overflow-auto py-1" data-product-id="{{ $product->id }}">
    <h4 class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
        Связанные продукты
        <button class="js-addVariant btn btn-success fs-5" type="button">
            <i data-feather="plus-square"></i>
        </button>
    </h4>
@foreach($related as $product)
    <div class="d-flex justify-content-start position-relative flex-nowrap variant" data-variant-id="{{ $product->id }}">
        <div class="col-auto align-self-center me-2">
            <div class="variant-image" data-variant-id="{{ $product->id }}">
                <button type="button" class="btn btn-outline-primary js-variantImg" data-variant-id="{{ $product->id }}">
                    @if(!$product->photos->isEmpty())
                        @php $photo = $product->photos[0] @endphp
                        <img src="{{ asset($photo->getPhoto('small')) }}" alt="{{ $photo->alt_tag }}">
                    @else
                        <i data-feather="camera-off"></i>
                    @endif
                </button>
            </div>
        </div>
        <div class="col-2 align-self-center me-2">
            <div class="form-text text-center">
                <h6>{{ $product->name }}</h6>
            </div>
        </div>
        <div class="col-auto align-self-center me-2">
            <div class="form-floating">
                <input type="text" id="variantSku-{{ $product->id }}" class="form-control @error('variant.sku') is-invalid @enderror"
                       name="variant.sku" value="{{ $product->sku }}" placeholder="Артикул продутка" disabled>
                <label for="variantSku-{{ $product->id }}">Артикул продукта</label>
                @error('variant.sku')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-auto align-self-center ms-auto">
            <div class="btn-group js-variantManage" role="group" aria-label="Manage variant">
                <a href="{{ route('admin.shop.products.edit', $product) }}" type="button" target="_blank" class="btn btn-outline-warning d-flex align-items-center">
                    <i data-feather="edit"></i>
                </a>
                <a href="{{ route('admin.shop.products.show', $product) }}" type="button" target="_blank" class="btn btn-outline-primary d-flex align-items-center">
                    <i data-feather="eye"></i>
                </a>
                <button type="button" class="btn btn-outline-danger" js-deleteVariant="{{ $product->id }}" data-current-product="{{ $current->id }}">
                    <i data-feather="x-circle"></i>
                </button>
            </div>
        </div>
    </div>
@endforeach
</variant-list>
