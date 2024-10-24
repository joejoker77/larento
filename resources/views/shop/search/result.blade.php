<?php
/**
 * @var $result App\Http\Requests\Products\SearchResult;
 * @var $products Illuminate\Pagination\LengthAwarePaginator
 * @var $product Product
 */


use App\Entities\Shop\Product;

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y'); ?>
@extends('layouts.index')
@section('content')
    <div id="category">
        <h1 class="h1">Результат поиска</h1>
        @if($products->isNotEmpty())
            <div class="d-flex justify-content-between flex-nowrap">
                <x-filter :request="$request" position="left" />
                <div class="category-contents">
                    <div class="sort-controls d-flex flex-row d-lg-none">
                        <button class="btn btn-outline-secondary text-capitalize" type="button">Сортировка</button>
                        <button class="btn btn-outline-secondary text-capitalize" type="button">Фильтр</button>
                    </div>
                    <div class="row product-sort mb-3 px-2">
                        <div class="form-group input-group-sm">
                            <select id="inputSort" onchange="location = this.value;" class="form-control mx-auto mx-sm-0" name="sort" aria-label="Сортировка">
                                <option>Сортировать по</option>
                                <option value="{{request()->fullUrlWithQuery(['sort' => 'name'])}}" @if(request('sort') == 'name') selected @endif>Имени А-Я</option>
                                <option value="{{request()->fullUrlWithQuery(['sort' => '-name'])}}" @if(request('sort') == '-name') selected @endif>Имени Я-А</option>
                                <option value="{{request()->fullUrlWithQuery(['sort' => '-hit'])}}" @if(request('sort') == '-hit') selected @endif>Сначала хиты продаж</option>
                                <option value="{{request()->fullUrlWithQuery(['sort' => '-new'])}}" @if(request('sort') == '-new') selected @endif>Сначала новинки</option>
                            </select>
                            <div class="d-flex justify-content-between justify-content-md-end">
                                <p class="ms-md-auto label-input-limit">Показать по:</p>
                                <div id="inputLimit">
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '6'])}}" @if(request('per-page') == '6') class="active" @endif>6</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => $settings['default_pagination']])}}" @if(request('per-page') == $settings['default_pagination'] or !request('per-page')) class="active" @endif>{{$settings['default_pagination']}}</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '30'])}}" @if(request('per-page') == '30') class="active" @endif>30</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '90'])}}" @if(request('per-page') == '90') class="active" @endif>90</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($products->chunk(3) as $chunk)
                        <div class="row px-0 px-md-2 mb-0 mb-md-3 mb-lg-5">
                            @foreach($chunk as $product)
                                <div class="product_item col-md-4 position-relative">
                                    <div class="product_item-images swiper">
                                        <div class="swiper-wrapper">
                                            @foreach($product->photos as $photo)
                                                <div class="product_item-image swiper-slide">
                                                    <a href="{{ route('catalog.index',product_path($product->category, $product)) }}">
                                                        <img src="{{ $photo->getPhoto('medium') }}" alt="{{ $photo->alt_tag }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                    <div class="product_item-info product_info position-relative">
                                        <a class="product_info-parent_cat_name" href="{{ route('catalog.index',product_path($product->category->parent, null)) }}">{{ $product->category->parent->title }}</a>
                                        <div class="product_info-product_name">
                                            <a href="{{ route('catalog.index',product_path($product->category, $product)) }}">{{ $product->name }}</a>
                                            <p>Цена по запросу</p>
                                        </div>
                                        <div class="product_info-product_options">
                                            <table>
                                                @foreach($product->values->sortBy('attribute_id') as $value)
                                                    @php /** @var $value App\Entities\Shop\Value */ @endphp
                                                    <tr>
                                                        @if($value->attribute->name == 'Стиль')
                                                            <td>{{ $value->attribute->name }}:</td><td>{{ $value->value }}@if($value->attribute->unit) {{ $value->attribute->unit }} @endif</td>
                                                        @elseif($value->attribute->name == 'Отделка фасадов')
                                                            <td>{{ $value->attribute->name }}:</td><td>{{ $value->value }}@if($value->attribute->unit) {{ $value->attribute->unit }} @endif</td>
                                                        @elseif($value->attribute->name == 'Форма')
                                                            <td>{{ $value->attribute->name }}:</td><td>{{ $value->value }}@if($value->attribute->unit) {{ $value->attribute->unit }} @endif</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                    <div class="product_item-order">
                                        <button class="btn btn-outline-brown text-uppercase" data-product_id="{{$product->id}}">Заказать</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    {{ $products->withPath('')->appends(request()->input())->links() }}
                    @include('components.order-form')
                </div>
            </div>
        @endif
    </div>
    <x-promotion-module />
@endsection
