@php use App\Entities\Shop\Product; @endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" id="productForm" action="{{ route('admin.shop.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row mt-4">
            <h3 class="mb-4 pb-4 border-bottom">Создание нового продукта</h3>
            <div class="col-md-9 base-form">
                <div class="p-3 mb-3 bg-light border ''">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" placeholder="Наименование продутка" required>
                                <label for="name">Наименование продукта</label>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="sku" class="form-control @error('sku') is-invalid @enderror"
                                       name="sku" value="{{ old('sku') }}" placeholder="Артикул продутка">
                                <label for="sku">Артикул продукта</label>
                                @error('sku')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        @include('layouts.partials.meta')
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border ''">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Описание товара</h6>
                            <div class="form-text">
                                <textarea id="description" name="description"
                                          class="ckeditor form-control @error('description') is-invalid @enderror"
                                          placeholder="Описание товара" rows="7">{{ trim(old('description')) }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 mb-3 bg-light border ''" id="newAttributes"></div>

                <div class="mb-3">
                    <div class="p-3 mb-3 bg-light border ''">
                        <up-images data-destination="category">
                            <label for="photo" class="form-label">Изображения продукта</label>
                            <div class="images-container"></div>
                            <input class="form-control @error('photo') is-invalid @enderror" name="photo[]" type="file" id="photo" multiple>
                            @error('photo')<span class="invalid-feedback">{{ $message }}</span> @enderror
                        </up-images>
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border ''">
                    <button type="submit" class="btn btn-success w-100">Сохранить</button>
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
                                <option value="{{ $category->id }}">{{ html_entity_decode(str_repeat('&mdash;', (int)$category->depth)) }}{{ $category->title ?: $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<span class="invalid-feedback">{{ $message }}</span>@enderror

                        <h6 class="my-3 pb-3 border-bottom">Дополнительные категории</h6>
                        @error('product.categories')
                        <div class="is-invalid"></div>@enderror
                        <select name="product.categories[]" class="js-choices" multiple data-placeholder="-=Выбрать категорию=-">
                            <option></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ html_entity_decode(str_repeat('&mdash;', (int)$category->depth)) }}{{ $category->title ?: $category->name }}</option>
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
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </input-tags>
                </div>
            </div>
        </div>
    </form>
@endsection
