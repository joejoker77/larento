@php
/**
 * @var $promotions Illuminate\Database\Eloquent\Collection
 * @var $promotion App\Entities\Site\Promotions\Promotion
 */

use App\Entities\Site\Promotions\Promotion;

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
$category      = \App\Entities\Blog\Category::scoped(['status'=>\App\Entities\Blog\Category::STATUS_ACTIVE])->where('slug', 'akcii')->first()
@endphp
@if($promotions->isNotEmpty())
    <div id="promotionModule">
        <div class="promotion_module-head-buttons d-flex flex-nowrap justify-content-between">
            <h3 class="h3">Акции компании</h3>
            <div class="swiper-button swiper-button-prev d-none d-lg-block">‹</div>
            <div class="swiper-button swiper-button-next d-none d-lg-block">›</div>
        </div>
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($promotions as $promotion)
                    <div class="promotion_item swiper-slide">
                        <div class="promotion_item-image">
                            <img src="{{ asset($promotion->getImage('thumb')) }}" alt="{{ $promotion->photo->alt_tag }}">
                            <div class="promotion_item-text position-absolute" style="@if($promotion->settings[Promotion::SETTINGS_POSITION_LEFT]) left:{{$promotion->settings[Promotion::SETTINGS_POSITION_LEFT]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_RIGHT]) right:{{$promotion->settings[Promotion::SETTINGS_POSITION_RIGHT]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_TOP]) top:{{$promotion->settings[Promotion::SETTINGS_POSITION_TOP]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_POSITION_BOTTOM]) bottom:{{$promotion->settings[Promotion::SETTINGS_POSITION_BOTTOM]}}px; @endif @if($promotion->settings[Promotion::SETTINGS_WIDTH_TEXT_BLOCK]) width:{{$promotion->settings[Promotion::SETTINGS_WIDTH_TEXT_BLOCK]}}px; @endif">
                                @if($promotion->description) <div class="h1">{!! $promotion->description !!}</div> @endif
                                @if($promotion->description_two) <div class="h2">{!! $promotion->description_two !!}</div> @endif
                            </div>
                        </div>
                        <div class="promotion_item-info">
                            {!! trim(mb_substr($promotion->description_three, 0, mb_strpos($promotion->description_three, '<hr />'))) !!}
                            @php $date = new DateTime($promotion->expiration_end); @endphp
                            <p class="small-text mt-auto">*Акция действует @if($promotion->expiration_end) до {{ $intlFormatter->format($date) }} года @else бессрочно @endif</p>
                        </div>
                        @if($category)
                            <a href="{{ route('blog.index', post_path($category, null)) }}#{{$promotion->slug}}" target="_blank" class="stretched-link"></a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


