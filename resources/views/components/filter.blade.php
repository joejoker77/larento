@php
    /** @var App\Entities\Shop\Filter $filter */

use App\Entities\Shop\Attribute;
use App\Entities\Shop\Category;
$currentCategoryId = $currentCategoryId ?? request('currentCategoryId');
@endphp
@if($filter)
    <div class="col-lg-3 filter-wrapper">
        <aside>
            <div id="beginFilter">
                <button class="btn btn-brown d-lg-none" id="closeFilter">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            @if(request()->get('attributes') || request()->get('tags') || request()->get('categories') || request()->get('colors') || request()->get('price'))
                <a href="{{route('shop.filter').'?currentCategoryId='.request('currentCategoryId')}}" type="button"
                   class="btn btn-sm btn-brown text-white fs-5 w-100 mb-3">Сбросить фильтр</a>
            @endif
            <form action="{{ route('shop.filter') }}" method="get" class="form-filter">
                <input type="hidden" name="currentCategoryId" value="{{ $currentCategoryId }}">
                @foreach($filter->groups as $group)
                    @if($group->categories)
                        <div class="filter-group-item">
                            <div class="filter-item @if(count($group->categories) > 3) collapsed @endif">
                                @if($group->display_header)
                                    <div class="filter-group-heading">
                                        {{ $group->name }}
                                        @if(count($group->categories) > 5)
                                            <button type="button" class="btn btn-link">Показать все</button>
                                        @endif
                                    </div>
                                @endif
                                @foreach($group->categories as $category)
                                    @php $category = Category::find($category) @endphp
                                    <div class="form-check custom-checkbox">
                                        <label for="category-{{$group->id}}-{{$category->id}}">{{ $category->name }}
                                            <input
                                                @checked(request()->get('categories') && in_array($category->id, request()->get('categories'))) id="category-{{$group->id}}-{{$category->id}}"
                                                type="checkbox" class="form-check-input" value="{{$category->id}}"
                                                name="categories[]">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(!empty($group['tags']))
                        @foreach($group['tags'] as $tag)
                            <div class="filter-item tag-item form-check custom-checkbox">
                                <label for="tag-{{$group['id']}}-{{$tag->id}}">{{ $tag->name }}
                                    <input id="tag-{{$group['id']}}-{{$tag->id}}" type="checkbox"
                                           class="form-check-input" value="{{$tag->id}}" name="tags[]">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    @if($group->sortAttributes)
                        @php $requestAttrs = request()->input('attributes'); @endphp
                        @foreach($group->sortAttributes as $attribute)
                            @php /** @var $attribute Attribute */ @endphp
                            @if($attribute->type === Attribute::TYPE_FLOAT || $attribute->type === Attribute::TYPE_INTEGER)
                                <div class="filter-group-item">
                                    @if($group['displayHeader'])
                                        <div class="filter-group-heading">
                                            {{ $groupName }}
                                        </div>
                                    @endif
                                    <div class="filter-item">
                                        <div class="attribute-header">
                                            {{ $attribute->name }}
                                        </div>
                                        <div class="attribute-item">
                                            @php $sortedVariants = $attribute->variants; @endphp
                                            <input type="hidden" id="attributeMin-{{$attribute->id}}"
                                                   name="attributes[{{ $attribute->id }}][min]"
                                                   value="@if(!empty(request()->get('attributes')[$attribute->id]['min'])){{request()->get('attributes')[$attribute->id]['min']}}@else{{get_min_from_string($sortedVariants, $attribute->unit).' '.$attribute->unit}}@endif"
                                                   js-name="minValue">
                                            <input type="hidden" id="attributeMax-{{$attribute->id}}"
                                                   name="attributes[{{ $attribute->id }}][max]"
                                                   value="@if(!empty(request()->get('attributes')[$attribute->id]['max'])){{request()->get('attributes')[$attribute->id]['max']}}@else{{get_max_from_string($sortedVariants, $attribute->unit).' '.$attribute->unit}}@endif"
                                                   js-name="maxValue">
                                            <div class="slider-styled"
                                                 data-min={{ $attribute->min }} data-max={{ $attribute->max }} data-steps="[{{ trim(implode(',', $sortedVariants), ',') }}]"
                                                 @if(!empty(request()->get('attributes'))) data-fact-min="{{request()->get('attributes')[$attribute->id]['min']}}"
                                                 data-fact-max="{{request()->get('attributes')[$attribute->id]['max']}}" @endif></div>
                                            <div class="filter-values d-flex justify-content-between">
                                                <div class="min-value-display" js-name="minValueDisplay">
                                                    @if(!empty(request()->get('attributes')[$attribute->id]['min']))
                                                        {{request()->get('attributes')[$attribute->id]['min']}}
                                                    @else
                                                        {{get_min_from_string($sortedVariants, $attribute->unit).' '.$attribute->unit}}
                                                    @endif
                                                </div>
                                                <div class="max-value-display" js-name="maxValueDisplay">
                                                    @if(!empty(request()->get('attributes')[$attribute->id]['max']))
                                                        {{request()->get('attributes')[$attribute->id]['max']}}
                                                    @else
                                                        {{get_max_from_string($sortedVariants, $attribute->unit).' '.$attribute->unit}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="filter-item @if((count($attribute->variants) > 5) || $attribute->selected) collapsed @endif">
                                    <div class="attribute-header">
                                        {{ $attribute->name }}
                                        @if((count($attribute->variants) > 5)  || $attribute->selected)
                                            <button type="button" class="btn btn-link">Показать все</button>
                                        @endif
                                    </div>
                                    <div class="attribute-item">
                                        @foreach($attribute->variants as $key => $variant)
                                            @continue(!$variant)
                                            <div class="variant form-check custom-checkbox">
                                                @php
                                                    if(strpbrk($variant, '|')) {
                                                        $arrayVariant = explode('|', $variant);
                                                        $variantLabel = $arrayVariant[0];
                                                        $variantStyle = $arrayVariant[1];
                                                    }
                                                    if ($attribute->type == Attribute::TYPE_BOOLEAN) {
                                                        $variantLabel = 'Да';
                                                    }
                                                @endphp
                                                <label for="variant-{{$group['id']}}-{{$attribute->id}}-{{$key}}">{{ $variantLabel ?? $variant }}
                                                    <input id="variant-{{$group['id']}}-{{$attribute->id}}-{{$key}}"
                                                           type="checkbox" class="form-check-input" value="{{$variant}}"
                                                           @checked(!empty($requestAttrs) && isset($requestAttrs[$attribute->id]) && in_array($variant, $requestAttrs[$attribute->id]['equals'])) name="attributes[{{$attribute->id}}][equals][]">
                                                    <span
                                                        class="checkmark @if(isset($variantStyle) && $variantStyle == 'ffffff')inverse @endif"
                                                        @if(isset($variantStyle))style="background: {{trim('#'.$variantStyle)}};border-radius: 9px" @endif></span>
                                                </label>
                                                @php unset($arrayVariant, $variantLabel, $variantStyle) @endphp
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if(request()->get('attributes') || request()->get('tags') || request()->get('categories') || request()->get('colors') || request()->get('price'))
                    <a href="{{route('shop.filter')}}" type="button" class="btn btn-brown text-white fs-5 w-100 mb-3">Сбросить фильтр</a>
                @endif
            </form>
            <div id="endFilter"></div>
        </aside>
    </div>
@endif
