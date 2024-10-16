@php /** @var \App\Entities\Shop\Attribute[] $attributes */ @endphp
@foreach($attributes as $attribute)
    @foreach($attribute->variants as $variant)
        @if(strpbrk($variant, '|'))
            @php $customTemplate = true @endphp
        @else
            @php $customTemplate = false @endphp
        @endif
    @endforeach
    <div class="row">
        <div class="col-2 mb-3 align-self-center">
            <h6>{{ $attribute->name }}</h6>
        </div>
        <div class="col-10 mb-3">
            @error('product.attributes')<div class="is-invalid"></div>@enderror
            <select
                name="product.attributes[{{ $attribute->id }}]@if($attribute->as_option || $attribute->mode == \App\Entities\Shop\Attribute::MODE_MULTIPLE)[]@endif"
                class="js-choices"
                data-attribute-id="{{ $attribute->id }}"
                data-placeholder="-=Выбрать {{ $attribute->name }}=-"
                @if($attribute->as_option || $attribute->mode == \App\Entities\Shop\Attribute::MODE_MULTIPLE) multiple @endif
                @if($customTemplate) data-custom-template @endif
            >
                <option></option>
                @foreach($attribute->variants as $variant)
                    <option value="{{ $variant }}"
                        @isset($product)
                        @foreach($product->values as $val)
                            @if($attribute->mode == \App\Entities\Shop\Attribute::MODE_MULTIPLE)
                                @php $valArray = array_map('trim', explode(',', $val->value)) @endphp
                                @if($val->attribute_id == $attribute->id && in_array($variant, $valArray)) selected @endif
                            @else
                                @php
                                $valValue = $attribute->unit ? trim(str_replace($attribute->unit, '', $val->value)) : $val->value
                                @endphp
                                @selected(($val->attribute_id == $attribute->id && $valValue === $variant) or ($attribute->type == \App\Entities\Shop\Attribute::TYPE_BOOLEAN && !$variant))
                            @endif
                        @endforeach
                        @endisset
                    >
                        @if($attribute->type == \App\Entities\Shop\Attribute::TYPE_BOOLEAN)
                            @if($variant) Да @else Нет @endif
                        @else
                            {{ $variant }}
                        @endif
                    </option>
                @endforeach
            </select>
            @error('product.attributes')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
@endforeach
