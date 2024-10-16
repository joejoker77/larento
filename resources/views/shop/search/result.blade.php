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
                <div class="category-contents w-75">
                    <div class="row product-sort mb-3 px-2">
                        <div class="col-6">
                            <div class="form-group input-group-sm">
                                <select id="inputSort" onchange="location = this.value;" class="form-control" name="sort" aria-label="Сортировка">
                                    <option>Сортировать по</option>
                                    <option value="{{request()->fullUrlWithQuery(['sort' => 'name'])}}" @if(request('sort') == 'name') selected @endif>Имени А-Я</option>
                                    <option value="{{request()->fullUrlWithQuery(['sort' => '-name'])}}" @if(request('sort') == '-name') selected @endif>Имени Я-А</option>
                                    <option value="{{request()->fullUrlWithQuery(['sort' => '-hit'])}}" @if(request('sort') == '-hit') selected @endif>Сначала хиты продаж</option>
                                    <option value="{{request()->fullUrlWithQuery(['sort' => '-new'])}}" @if(request('sort') == '-new') selected @endif>Сначала новинки</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 text-end pe-0">
                            <div class="d-flex">
                                <p class="ms-auto label-input-limit">Показать по:</p>
                                <div id="inputLimit">
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '3'])}}" @if(request('per-page') == '3') class="active" @endif>10</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '20'])}}" @if(request('per-page') == '20' or !request('per-page')) class="active" @endif>20</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '50'])}}" @if(request('per-page') == '50') class="active" @endif>50</a>
                                    <a href="{{request()->fullUrlWithQuery(['per-page' => '100'])}}" @if(request('per-page') == '100') class="active" @endif>100</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($products->chunk(3) as $chunk)
                        <div class="row ps-2 mb-5">
                            @foreach($chunk as $product)
                                <div class="product_item col-md-4">
                                    <div class="product_item-images swiper">
                                        <div class="swiper-wrapper">
                                            @foreach($product->photos as $photo)
                                                <div class="product_item-image swiper-slide">
                                                    <a href="{{ route('catalog.index',product_path($product->category, $product)) }}">
                                                        <img src="{{ $photo->getPhoto('thumb') }}" alt="{{ $photo->alt_tag }}">
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
                    <div class="order_product" style="background: #786B61 url('{{ asset('storage/images/inner pages/order-form-background.webp') }}') top right/contain no-repeat">
                        <div class="order_product-form w-50">
                            <div class="h1 text-white">Бесплатный дизайн проект</div>
                            <p>При заказе любой кухни, мы изготовим дизайн проект бесплатно. Закажите кухню своей мечты прямо сейчас</p>
                            <form action="{{ route('shop.order') }}" method="post">
                                @csrf
                                <div class="input-group mb-2">
                                    <div class="form-floating me-3">
                                        <input type="text" class="form-control" id="userName" placeholder="Имя" autocomplete="off" required>
                                        <label for="userName">Имя</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="userPhone" placeholder="+7" autocomplete="off" required>
                                        <label for="userPhone">+7</label>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="userPrivacy" checked disabled>
                                    <label class="form-check-label" for="userPrivacy">
                                        Нажимая на кнопку отправить Вы соглашаетесь с политикой конфиденциальности
                                    </label>
                                </div>
                                <button class="btn btn-outline-light" type="submit">Отправить</button>
                                <p class="notify-user">*Проект предоставляется клиенту бесплатно в случае приобретения продукции компании Lorento</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <x-promotion-module />
@endsection
