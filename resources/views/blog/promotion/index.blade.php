@php
    /** @var App\Entities\Blog\Category $category */

use App\Entities\Site\Promotions\Promotion;
$promotions = Promotion::where('settings->is_main_banner', null)->with('photo')->active()->get();
$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.index')
@section('content')
    <div class="blog promotion" id="blogCategoryPage">
        <div class="row">
            <h1 class="h1">Акции компании Larento</h1>
            <div class="col-lg-3">
                <aside class="w-100 sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="w-75">
                @foreach($promotions as $promotion)
                    <div class="mb-5 promotion_item" id="{{$promotion->slug}}">
                        <div class="d-flex flex-nowrap flex-row justify-content-start gap-4 w-100">
                            <div class="promotion_item-image">
                                <img src="{{ asset($promotion->getImage('medium')) }}" alt="{{ $promotion->photo->alt_tag }}">
                            </div>
                            <div class="promotion_item-info d-flex flex-column">
                                <div class="promotion_item-head">{!! $promotion->title !!}</div>
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
