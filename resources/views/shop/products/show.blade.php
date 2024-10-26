<?php /** @var $product App\Entities\Shop\Product */ ?>
@extends('layouts.index')
@section('content')
    <div id="productPage">
        <div class="row mb-3 mb-lg-5">
        @if(!$product->photos->isEmpty())
            <div class="col-md-6 px-0 px-md-2">
                <main-gallery>
                    <div class="swiper full-swiper">
                        <div class="swiper-wrapper">
                            @php /** @var App\Entities\Shop\Photo $photo */ @endphp
                            @foreach($product->photos as $photo)
                                <div class="swiper-slide full">
                                    <img src="{{ $photo->getPhoto('medium') }}" alt="{{ $photo->alt_tag }} @if($photo->alt_tag) размер средний @endif">
                                </div>
                            @endforeach
                        </div>
                        @if($product->photos()->count() > 1)
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
                    @if($product->photos()->count() > 1)
                        <div class="swiper thumbs-swiper d-none d-md-block">
                            <div class="swiper-wrapper">
                                @foreach($product->photos as $photo)
                                    <div class="swiper-slide thumb">
                                        <img src="{{ $photo->getPhoto('small') }}" alt="{{ $photo->alt_tag }} @if($photo->alt_tag) размер маленький @endif">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </main-gallery>
            </div>
        @endif
        <div class="col-md-6 product_offer">
            <h1 class="d-flex justify-content-between align-items-baseline">
                {{$product->name}}
            </h1>
            <div class="product_offer-buttons d-flex flex-column flex-xl-row flex-nowrap gap-3">
                <button type="button" name="js-modal" class="btn btn-brown" value="Вызов змерщика" data-product-name="{{$product->name}}">Вызов замерщика</button>
                <button type="button" name="js-modal" class="btn btn-outline-brown" value="Консультация менеджера" data-product-name="{{$product->name}}">Консультация менеджера</button>
            </div>
            <div class="product_offer-info">
                <div class="product_offer-info_design">
                    Закажите мебель под ключ у Larento и получите индивидуальный дизайн проект совершенно бесплатно. Откройте для себя мир возможностей, которые мы предлагаем, и сделайте вашу кухню местом, где всегда приятно побыть.
                </div>
                <div class="product_offer-info_installment">
                    Обращение к компании Larento за беспроцентной рассрочкой может стать отличным решением, которое удовлетворит потребности каждого клиента.
                </div>
                <div class="product_offer-info_sample">
                    Стоимость замера помещения в пределах МКАД - 2500 ₽. При оформлении данная сумма возвращается.
                </div>
                <div class="product_offer-info_guarantee">
                    Компания Larento, предоставляет гарантию на свою продукцию сроком на два года. Гарантия распространяется на всю мебель за исключением бытовой техники. Гарантия на бытовую технику дается производителем.
                </div>
            </div>
            <div class="product_offer-block">
                <table class="d-none d-lg-table">
                    <tr>
                        <td>Дизайн проект</td>
                        <td>Рассрочка</td>
                    </tr>
                    <tr>
                        <td>Замер</td>
                        <td>Гарантия</td>
                    </tr>
                </table>
                <div class="d-flex flex-column d-lg-none">
                    <div class="product_offer-block-item">Дизайн проект</div>
                    <div class="product_offer-block-item">Рассрочка</div>
                    <div class="product_offer-block-item">Замер</div>
                    <div class="product_offer-block-item">Гарантия</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row product-info">
        <h2 class="h2">Описание</h2>
        <div class="product-description">
            {!! $product->description !!}
        </div>
        <div class="product-attributes">
            <div class="h2">Характеристики</div>
            @php $productValuesArrayIDs = array_column($product->values->toArray(), 'attribute_id'); @endphp
            @foreach($product->category->allAttributes()->toArray() as $attribute)
                @php $res = array_search($attribute['id'], $productValuesArrayIDs); @endphp
                @if($res !== false || ($res === false && $attribute['type'] === \App\Entities\Shop\Attribute::TYPE_BOOLEAN))
                    <div class="d-flex flex-nowrap flex-row justify-content-between">
                        <div class="attribute-name">
                            {{ $attribute['name'] }}
                        </div>
                        <div class="border-dotted flex-fill"></div>
                        <div class="attribute-value">
                            @if($res !== false)
                                @if($attribute['type'] == \App\Entities\Shop\Attribute::TYPE_BOOLEAN)
                                    ДА
                                @elseif(strpbrk($product->values->firstWhere('attribute_id', $productValuesArrayIDs[$res])->value, '|'))
                                    @php $valueArray = $product->values->firstWhere('attribute_id', $productValuesArrayIDs[$res])->parseValue() @endphp
                                    @foreach($valueArray as $key => $array)
                                        <span style="background:{{trim('#'.$array[1])}}"></span>
                                        {{$array[0] }}@if(count($valueArray) != $key+1),&nbsp;@endif
                                    @endforeach
                                @else
                                    {{$product->values->firstWhere('attribute_id', $productValuesArrayIDs[$res])->value}}
                                @endif
                            @elseif($attribute['type'] == \App\Entities\Shop\Attribute::TYPE_BOOLEAN)
                                НЕТ
                            @endif
                        </div>
                        @unset($res)
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="row add-comment">
        <div class="col-md-6">
            <h4 class="h5">Комментарии</h4>
            <p class="sub-title">
                Оставьте свой отзыв или комментарий. Внимание! Комментарий может не пройти модерацию и быть удален, если он не соответствует правилам, изложеным ниже:
            </p>
            <ul>
                <li>Размещение допустимо без вложений</li>
                <li>Комментарий не должен содержать оскорбления или угрозы</li>
                <li>Запрещается использование нецензурной лексики</li>
                <li>Запрещается пропаганда ненависти, дискриминации по расовому, этническому, половому, религиозному, социальному признакам</li>
                <li>Запрещается распространение персональных данных третьих лиц без их согласия</li>
                <li>Плохая стилистика или орфография.</li>
            </ul>
            <button class="popover-link"
               data-bs-custom-class="custom-popover"
               data-bs-toggle="popover"
               data-bs-title="Комментарий будет удален, если:"
               data-bs-content="<ul><li>пропагандирует ненависть, дискриминацию по расовому, этническому, половому, религиозному, социальному признакам, содержит оскорбления, угрозы в адрес других пользователей, конкретных лиц или организаций, ущемляет права меньшинств, нарушает права несовершеннолетних, причиняет им вред в любой форме;</li><li>порочит честь и достоинство других лиц или подрывает их деловую репутацию;</li><li>распространяет персональные данные третьих лиц без их согласия;</li><li>преследует коммерческие цели, содержит спам, рекламную информацию или ссылки на другие сетевые ресурсы, содержащие такую информацию;</li><li>имеет непристойное содержание, содержит нецензурную лексику и её производные, а также намёки на употребление лексических единиц, подпадающих под это определение;</li><li>является частью акции, при которой поступает большое количество комментариев с идентичным или схожим содержанием («флешмоб»);</li><li>автор злоупотребляет написанием большого количества малосодержательных сообщений («флуд»);</li><li>смысл текста трудно или невозможно уловить;</li><li>текст целиком или преимущественно набран заглавными буквами;</li><li>текст не разбит на предложения.</li></ul>"
               data-bs-plasement="top">
                Полное описание
            </button>
        </div>
        <div class="col-md-6 ps-lg-4">
            <h6 class="h6">Оставить комментарий</h6>
            <form action="{{ route('shop.add-comment', $product) }}" method="POST" id="sendComment">
                @csrf
                <div class="d-flex flex-row justify-content-start mb-4">
                    <div class="label-for-rating">Ваша оценка&nbsp;&nbsp;&mdash;&nbsp;&nbsp;</div>
                    <div class="rating-holder">
                        <div class="c-rating c-rating--big" data-rating-value="0">
                            <span>1</span>
                            <span>2</span>
                            <span>3</span>
                            <span>4</span>
                            <span>5</span>
                        </div>
                        <input type="hidden" name="vote" value="0">
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="userName" name="user_name" placeholder="Имя" autocomplete="off" required="" value="{{ old('user_name') }}">
                    <label for="userName">Имя</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control" placeholder="Напишите здесь свой комментарий" id="commentText" style="height: 100px" name="comment" required>{{ old('comment') }}</textarea>
                    <label for="commentText">Ваш комментарий</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="userPrivacy" checked="" disabled="">
                    <label class="form-check-label" for="userPrivacy">
                        Нажимая на кнопку отправить Вы соглашаетесь с политикой конфиденциальности
                    </label>
                </div>
                <button class="btn btn-lg btn-outline-light mb-3" type="submit">Отправить на модерацию</button>
            </form>
        </div>
    </div>
</div>
    <x-promotion-module />
@endsection
