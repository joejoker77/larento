@php
    /** @var App\Entities\Blog\Category $category */

use App\Entities\Site\Promotions\Promotion;
$promotions = Promotion::where('settings->is_main_banner', null)->with('photo')->active()->get();
$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.index')
@section('content')
    <div class="blog promotion" id="blogCategoryPage">
        <h1 class="h1">Акции компании Larento</h1>
        <div class="d-flex flex-column flex-lg-row">
            <div class="col col-lg-3 position-relative">
                <aside class="sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="col col-lg-9">
                @foreach($promotions as $promotion)
                    <div class="mb-5 promotion_item" id="{{$promotion->slug}}">
                        <div class="d-flex flex-nowrap flex-column flex-lg-row justify-content-start gap-4 w-100">
                            <div class="promotion_item-head d-block d-lg-none">{!! $promotion->title !!}</div>
                            <div class="promotion_item-image">
                                <img src="{{ asset($promotion->getImage('medium')) }}" alt="{{ $promotion->photo->alt_tag }} @if($promotion->photo->alt_tag) размер средний @endif">
                            </div>
                            <div class="promotion_item-info d-flex flex-column">
                                <div class="promotion_item-head d-none d-lg-block">{!! $promotion->title !!}</div>
                                {!! trim(str_replace('<hr />', '', mb_substr($promotion->description_three, mb_strpos($promotion->description_three, '<hr />')))) !!}
                                @php $date = new DateTime($promotion->expiration_end); @endphp
                                <p class="small-text mb-0 mt-auto ms-auto">*Акция действует @if($promotion->expiration_end) до {{ $intlFormatter->format($date) }} года @else бессрочно @endif</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                @include('components.order-form')
            </div>
        </div>
    </div>
@endsection
