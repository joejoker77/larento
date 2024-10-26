@php
    /** @var App\Entities\Blog\Category $category */

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.index')
@section('content')
    <div class="blog portfolio">
        <h1 class="h1">{{ $category->title }}</h1>
        <div class="d-flex flex-column flex-lg-row">
            <div class="col-lg-3">
                <aside class="sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="col-lg-9">
                {!! $category->description !!}
                @php /** @var $post App\Entities\Blog\Post */ @endphp
                @foreach($posts->sortByDesc('id') as $post)
                    <div class="d-flex flex-nowrap flex-column flex-md-row justify-content-between pb-2 mb-3 pb-lg-5 mb-lg-5 border-bottom portfolio_item">
                        <h3 class="h3 d-block d-md-none">
                            <a href="{{ route('blog.index', post_path($post->category, $post)) }}">{{ $post->title }}</a>
                        </h3>
                        <main-gallery class="col-md-7" data-size-replace="large">
                            <div class="swiper full-swiper">
                                <div class="swiper-wrapper">
                                    @php /** @var App\Entities\Shop\Photo $photo */ @endphp
                                    @foreach($post->photos as $photo)
                                        <div class="swiper-slide full">
                                            <img src="{{ $photo->getPhoto('large') }}" alt="{{ $photo->alt_tag }} @if($photo->alt_tag) размер большой @endif">
                                        </div>
                                    @endforeach
                                </div>
                                @if($post->photos->count() > 1)
                                    <div class="swiper-button swiper-button-next d-none d-lg-block"></div>
                                    <div class="swiper-button swiper-button-prev d-none d-lg-block"></div>
                                @endif
                                <div class="gallery-controls">
                                    <div class="full-screen-button">
                                        <span class="open material-symbols-outlined" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-title="Полноэкранный режим">fullscreen</span>
                                        <span class="close material-symbols-outlined d-none" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-title="Выйти из полноэкранного режима">close_fullscreen</span>
                                    </div>
                                </div>
                            </div>
                            @if($post->photos->count() > 1)
                                <div class="swiper thumbs-swiper d-none d-lg-block">
                                    <div class="swiper-wrapper">
                                        @foreach($post->photos as $photo)
                                            <div class="swiper-slide thumb">
                                                <img src="{{ $photo->getPhoto('small') }}" alt="{{ $photo->alt_tag }} @if($photo->alt_tag) размер маленький @endif">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </main-gallery>
                        <div class="info col-md-5">
                            <h3 class="h3 d-none d-md-block">
                                <a href="{{ route('blog.index', post_path($post->category, $post)) }}">{{ $post->title }}</a>
                            </h3>
                            <div class="description">{!! $post->content !!}</div>
                            <div class="text-end text-date">Дата публикации: {{ $intlFormatter->format($post->created_at) }}</div>
                        </div>
                    </div>
                @endforeach
                {{ $posts->withPath('')->appends(request()->input())->links() }}
            </div>
        </div>
        <x-promotion-module />
    </div>
@endsection
