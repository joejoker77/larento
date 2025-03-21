@php /** @var App\Entities\Blog\Post $post */ @endphp
@extends('layouts.index')
@section('content')
    <div class="post portfolio" id="postPage">
        <h1 class="h1">{{$post->title}}</h1>
        <div class="d-flex flex-column flex-lg-row mb-0 mb-lg-5">
            <div class="col-lg-3">
                <aside class="sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="col-lg-9">
                <div class="d-flex flex-nowrap flex-column flex-md-row justify-content-between pb-2 mb-3 pb-lg-5 mb-lg-5 border-bottom portfolio_item">
                    <main-gallery data-sizeReplace="large" class="col-md-7">
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
                                <div class="swiper-button swiper-button-next"></div>
                                <div class="swiper-button swiper-button-prev"></div>
                            @endif
                            <div class="gallery-controls">
                                <div class="full-screen-button">
                                    <span class="open material-symbols-outlined" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-title="Полноэкранный режим">fullscreen</span>
                                    <span class="close material-symbols-outlined d-none" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-title="Выйти из полноэкранного режима">close_fullscreen</span>
                                </div>
                            </div>
                        </div>
                        @if($post->photos->count() > 1)
                            <div class="swiper thumbs-swiper">
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
                    <div class="col-lg-5 d-flex flex-column description">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-promotion-module />
@endsection
