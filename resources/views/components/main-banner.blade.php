<?php
/**
 * @var Illuminate\Database\Eloquent\Collection $promotions
 * @var App\Entities\Site\Promotions\Promotion $promotion
 */

use App\Entities\Site\Promotions\Promotion;

?>
@if($promotions->isNotEmpty())
    <div id="mainBanner" class="swiper">
        <div class="swiper-wrapper">
            <!-- Slides -->
            @foreach($promotions as $promotion)
                <div class="swiper-slide">
                    <img src="{{ asset($promotion->getImage('full')) }}" alt="{{ $promotion->photo->alt_tag }} @if($promotion->photo->alt_tag) полный размер @endif">
                    <div class="text-block position-absolute" style="@if($promotion->settings[Promotion::SETTINGS_POSITION_LEFT]) left:{{$promotion->settings[Promotion::SETTINGS_POSITION_LEFT]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_RIGHT]) right:{{$promotion->settings[Promotion::SETTINGS_POSITION_RIGHT]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_TOP]) top:{{$promotion->settings[Promotion::SETTINGS_POSITION_TOP]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_BOTTOM]) bottom:{{$promotion->settings[Promotion::SETTINGS_POSITION_BOTTOM]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_WIDTH_TEXT_BLOCK]) width:{{$promotion->settings[Promotion::SETTINGS_WIDTH_TEXT_BLOCK]}}px; @endif">
                        @if($promotion->description) <div class="h1">{!! $promotion->description !!}</div> @endif
                        @if($promotion->description_two) <div class="h2">{!! $promotion->description_two !!}</div> @endif
                    </div>
                    @if((isset($promotion->settings[Promotion::SETTINGS_HIDE_TEXT]) && !$promotion->settings[Promotion::SETTINGS_HIDE_TEXT]) || !isset($promotion->settings[Promotion::SETTINGS_HIDE_TEXT]))
                        <div class="copyright">*Акция действует на все товары бренда larento представленных на нашем сайте. Колличество товаров по акции ограниченно сркоом проведения акции. Уточняйте информациию у менеджера компании.</div>
                    @endif
                </div>
            @endforeach
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button swiper-button-prev d-none d-lg-block">‹</div>
        <div class="swiper-button swiper-button-next d-none d-lg-block">›</div>
    </div>
@endif

