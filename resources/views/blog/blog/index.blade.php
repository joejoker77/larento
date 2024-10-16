@php /** @var App\Entities\Blog\Category $category */

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.index')
@section('content')
    <div class="blog" id="blogCategory">
        <div class="row">
            <h1 class="h1">{{ $category->title }}</h1>
            <div class="col-lg-3">
                <aside class="w-100 sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="w-75 mb-5">
                @if($category->photos->count() == 1)
                    <div class="category_photo-main w-50 mx-auto text-center mb-5">
                        <img src="{{ $category->getMainImage('medium') }}" alt="{{ $category->photos->first()->alt_tag }}">
                    </div>
                @endif
                {!! $category->description !!}
            </div>
        </div>
    </div>
    <x-promotion-module />
@endsection
