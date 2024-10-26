@php /** @var App\Entities\Blog\Category $category */

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.index')
@section('content')
    <div class="blog" id="blogCategory">
        <h1 class="h1">{{ $category->title }}</h1>
        <div class="d-flex flex-column flex-lg-row">
            <div class="col-lg-3">
                <aside class="sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="col-lg-9 mb-5">
                @if($category->photos->count() == 1)
                    <div class="category_photo-main mx-md-auto text-center mb-3 mb-lg-5">
                        <img src="{{ $category->getMainImage('medium') }}" alt="{{ $category->photos->first()->alt_tag }} @if($category->photos->first()->alt_tag) размер средний @endif">
                    </div>
                @endif
                {!! $category->description !!}
            </div>
        </div>
    </div>
    <x-promotion-module />
@endsection
