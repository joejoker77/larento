<?php
/**
 * @var $categories App\Entities\Shop\Category[]
 * @var $category App\Entities\Shop\Category
 * @var $products App\Entities\Shop\Product[]
 * @var $commentaries App\Entities\Shop\Comment[]
 */


use App\Entities\Shop\Product;

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
?>
@extends('layouts.index')
@section('content')
    <div id="category">
        <h1 class="h1">@if($category) {{ $category->title ?? $category->name }} @else Каталог @endif</h1>
        @if (!empty($categories))
            <div class="categories d-flex flex-wrap justify-content-between gap-3">
                @foreach($categories as $cat)
                    <div class="category position-relative">
                        <div class="category_image">
                            @if($catPhoto = $cat->photos()->first())
                                <img src="{{ $catPhoto->getPhoto('medium') }}" alt="{{ $catPhoto->alt_tag }} @if($catPhoto->alt_tag) размер средний @endif">
                            @else
                                <span class="material-symbols-outlined">no_photography</span>
                            @endif
                        </div>
                        <div class="category_info">
                            <h2 class="h4">{{$cat->title ?? $cat->name}}</h2>
                            {!!$cat->short_description!!}
                        </div>
                        <a class="stretched-link" href="{{ route('catalog.index',array_merge(['product_path' => product_path($cat, null)], request()->all())) }}"></a>
                    </div>
                @endforeach
            </div>
        @else
        <div class="d-flex justify-content-between flex-nowrap">
            <x-filter position="left" currentCategoryId="{{ $category->id }}" />
            <div class="category-contents">
                @if($products->isNotEmpty())
                    <div class="sort-controls d-flex flex-row d-lg-none">
                        <button class="btn btn-outline-secondary text-capitalize" type="button">Сортировка</button>
                        <button class="btn btn-outline-secondary text-capitalize" type="button">Фильтр</button>
                    </div>
                    <div class="row product-sort mb-3 px-2">
                        <div class="form-group input-group-sm">
                            <select id="inputSort" onchange="location = this.value;" class="form-control mx-auto mx-sm-0" name="sort" aria-label="Сортировка">
                                <option value="{{url()->current()}}">Сортировать по</option>
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
                            @php /** @var $product Product */ @endphp
                            @foreach($chunk as $product)
                                <div class="product_item col-md-4 position-relative">
                                    <div class="label @if($product->isHit())hit @endif @if($product->isNew())new @endif"></div>
                                    <div class="product_item-images swiper">
                                        <div class="swiper-wrapper">
                                            @foreach($product->photos as $photo)
                                                <div class="product_item-image swiper-slide">
                                                    <a href="{{ route('catalog.index',product_path($category, $product)) }}">
                                                        <img src="{{ $photo->getPhoto('medium') }}" alt="{{ $photo->alt_tag }} @if($photo->alt_tag) размер средний @endif">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                    <div class="product_item-info product_info position-relative">
                                        <a class="product_info-parent_cat_name" href="{{ route('catalog.index',product_path($product->category->parent, null)) }}">{{ $product->category->parent->title }}</a>
                                        <div class="product_info-product_name">
                                            <a href="{{ route('catalog.index',product_path($category, $product)) }}" class="product-name">{{ $product->name }}</a>
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
                                        <button class="btn btn-outline-brown text-uppercase" name="js-modal" data-product_id="{{$product->id}}" data-product_name="{{ $product->name }}">Заказать</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    {{ $products->withPath('')->appends(request()->input())->links() }}
                @endif
                @if($category)
                    @include('components.order-form')
                    <div class="category-info pt-5">
                        {!! $category->description !!}
                    </div>
                @endif
                @if($commentaries->isNotEmpty())
                    <div class="commentaries">
                        <h3 class="h3">Комментарии</h3>
                        <div class="swiper swiper-scrollbar-block">
                            <div class="commentaries_block d-flex flex-column swiper-wrapper">
                                @php /** @var App\Entities\Shop\Comment $comment */ @endphp
                                @foreach($commentaries as $comment)
                                    <div class="commentary d-flex flex-column flex-md-row justify-content-start swiper-slide">
                                        <div class="commentary_info">
                                            <div class="commentary_info-user">
                                                <img src="{{ asset('/storage/images/home/avatar-svgrepo-com 1.png') }}" alt="user avatar">
                                                {{ $comment->user_name }}
                                            </div>
                                            <a href="{{ route('catalog.index', product_path($category, $comment->product)) }}" class="h6">{{ $comment->product->name }}</a>
                                            <div class="rating-holder">
                                                <div class="c-rating c-rating" data-rating-value="{{ $comment->vote }}">
                                                    <span>1</span>
                                                    <span>2</span>
                                                    <span>3</span>
                                                    <span>4</span>
                                                    <span>5</span>
                                                </div>
                                            </div>
                                            <div class="commentary_info-date">
                                                {{ $intlFormatter->format(new DateTime($comment->created_at)) }}
                                            </div>
                                        </div>
                                        <div class="commentary-content w-100 d-flex flex-column justify-content-end ps-0 ps-lg-4 pb-0 pb-lg-4">
                                            {{ $comment->comment }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-scrollbar swiper-scrollbar-vertical"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    <x-promotion-module />
@endsection
