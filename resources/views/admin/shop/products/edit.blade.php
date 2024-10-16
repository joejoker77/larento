@php use App\Entities\Shop\Product; @endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" id="productForm" action="{{ route('admin.shop.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row mt-4">
            <h3 class="mb-4 pb-4 border-bottom">Редактирование продукта: "{{ $product->name }}"</h3>
            <div class="col-md-9 base-form">
                <div class="p-3 mb-3 bg-light border">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $product) }}" placeholder="Наименование продутка" required>
                                <label for="name">Наименование продукта</label>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="sku" class="form-control @error('sku') is-invalid @enderror"
                                       name="sku" value="{{ old('sku', $product) }}" placeholder="Артикул продутка">
                                <label for="sku">Артикул продукта</label>
                                @error('sku')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        @include('layouts.partials.meta', ['meta' => $product->meta])
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Описание товара</h6>
                            <textarea id="description" name="description" aria-label="Описание продукта"
                                      class="ckeditor form-control @error('description') is-invalid @enderror"
                                      placeholder="Полное описание" rows="7">{{ trim(old('description', $product)) }}</textarea>
                            @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="p-3 mb-3 bg-light border ''">
                    <h4 class="my-3 pb-3 border-bottom">Опции продукта</h4>
                    @include('admin.shop.products.partials.attributes', ['attributes' => $product->category->allAttributes(), 'product' => $product])
                </div>

                <div class="p-3 mb-3 bg-light border ''">
                    @include('admin.shop.products.partials.related', ['related' => $product->related, 'current' => $product])
                </div>
            </div>
            <div class="col-md-3 adding-forms">
                @if(!$categories->isEmpty())
                    <div class="p-3 mb-3 bg-light border ''">
                        <h6 class="my-3 pb-3 border-bottom">Основная категория</h6>
                        @error('category_id')<div class="is-invalid"></div>@enderror
                        <select name="category_id" class="js-choices" data-placeholder="-=Выбрать категорию=-">
                            <option></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if($category->id === $product->category->id) selected @endif>
                                    {{ html_entity_decode(str_repeat('&mdash;', (int)$category->depth)) }}{{ $category->title ?: $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <h6 class="my-3 pb-3 border-bottom">Дополнительные категории</h6>
                        @error('product.categories')
                        <div class="is-invalid"></div>@enderror
                        <select name="product.categories[]" class="js-choices" multiple data-placeholder="-=Выбрать категорию=-">
                            <option></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                @foreach($product->categories as $cat)
                                    @selected($cat->id === $category->id)
                                    @endforeach
                                >
                                    {{ html_entity_decode(str_repeat('&mdash;', (int)$category->depth)) }}{{ $category->title ?: $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product.categories')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                @endif
                <div class="p-3 mb-3 bg-light border ''">
                    <h6 class="my-3 pb-3 border-bottom">Теги</h6>
                    <input-tags>
                        <select name="product.tags[]" id="productTags" class="js-custom-choices" multiple>
                            <option value="">-=Выбрать или создать тег=-</option>
                            @if($tags)
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                    @foreach($product->tags as $tg)
                                        @selected($tg->id === $tag->id)
                                    @endforeach
                                    >
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </input-tags>
                </div>
            </div>
        </div>
    </form>
    <div class="p-3 mb-3 bg-light border">
        <up-images>
            <label for="photo" class="form-label">Изображения продукта</label>
            <div class="images-container">
                @foreach($product->photos as $image)
                    <div class="image-item">
                        <div class="wrapper-image" data-photo-id="{{ $image->id }}" data-photo-owner="product" data-product-id="{{ $product->id }}">
                            <img src="{{ asset($image->getPhoto('small')) }}" alt="{{ $image['alt'] }}">
                        </div>
                        <div class="image-control btn-group">
                            <form action="{{ route('admin.shop.products.photo.up', [$product,$image]) }}" method="POST" class="btn btn-secondary">
                                @csrf
                                <button type="submit" class="btn p-0 text-white d-flex">
                                    <span data-feather="arrow-left-circle"></span>
                                </button>
                            </form>
                            <form action="{{ route('admin.shop.products.photo.remove', [$product,$image]) }}" method="POST" class="btn btn-danger">
                                @csrf
                                <button type="submit" class="btn p-0 text-white d-flex js-confirm">
                                    <span data-feather="x-circle"></span>
                                </button>
                            </form>
                            <form action="{{ route('admin.shop.products.photo.down', [$product,$image]) }}" method="POST" class="btn btn-secondary">
                                @csrf
                                <button type="submit" class="btn p-0 text-white d-flex">
                                    <span data-feather="arrow-right-circle"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <input form="productForm" class="form-control @error('photo') is-invalid @enderror" name="photo[]" type="file" id="photo" multiple>
            @error('photo')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </up-images>
    </div>
    <div class="p-3 mb-3 bg-light border">
        <button form="productForm" type="submit" class="btn btn-success w-100">Сохранить</button>
    </div>
@endsection
