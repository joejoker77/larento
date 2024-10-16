<?php
/**
 * @var $promotion Promotion
 * @var $photo App\Entities\Shop\Photo
 */
use \App\Entities\Site\Promotions\Promotion;
?>
@extends('layouts.admin')
@section('content')
    <form method="post" id="promotionForm" action="{{ route('admin.shop.promotion.update', $promotion) }}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="row mt-4">
            <h3 class="mb-4 pb-4 border-bottom">Создание новой акции/баннера</h3>
            <div class="col-md-9 base-form">
                <div class="p-3 mb-3 bg-light border">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ $promotion->name }}" placeholder="-=Наименование акции=-" required>
                                <label for="name">-=Наименование акции=-</label>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                                       name="title" value="{{ $promotion->title }}" placeholder="-=Заголовок акции=-">
                                <label for="title">-=Заголовок акции=-</label>
                                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Текст акции</h6>
                            <div class="form-text">
                                <textarea id="description" name="description" aria-label="-=Описание акции=-"
                                          class="ckeditor form-control @error('description') is-invalid @enderror"
                                          placeholder="-=Описание акции=-" rows="7">{{ $promotion->description }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Текст акции (дополнительно)</h6>
                            <div class="form-text">
                                <textarea id="description_two" name="description_two" aria-label="-=Описание акции (дополнительно)=-"
                                          class="ckeditor form-control @error('description_two') is-invalid @enderror"
                                          placeholder="-=Описание акции (дополнительно)=-" rows="7">{{ $promotion->description_two }}</textarea>
                                @error('description_two')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <h6>Текст акции (описание)</h6>
                        <div class="form-text">
                                <textarea id="description_three" name="description_three" aria-label="-=Текст акции (описание)=-"
                                          class="ckeditor form-control @error('description_three') is-invalid @enderror"
                                          placeholder="-=Текст акции (описание)=-" rows="7">{!! trim($promotion->description_three) !!}</textarea>
                            @error('description_three')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12">
                            @include('layouts.partials.meta', ['meta' => $promotion->meta, 'required' => false])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 adding-forms">
                <div class="p-3 mb-3 bg-light border">
                    <h6 class="my-3 pb-3 border-bottom">Настройки акции</h6>
                        <?php
                        $settings_position_left    = 'settings['.Promotion::SETTINGS_POSITION_LEFT.']';
                        $settings_position_right   = 'settings['.Promotion::SETTINGS_POSITION_RIGHT.']';
                        $settings_position_top     = 'settings['.Promotion::SETTINGS_POSITION_TOP.']';
                        $settings_position_bottom  = 'settings['.Promotion::SETTINGS_POSITION_BOTTOM.']';
                        $settings_width_text_block = 'settings['.Promotion::SETTINGS_WIDTH_TEXT_BLOCK.']';
                        $settings_is_main_banner   = 'settings['.Promotion::SETTINGS_IS_MAIN_BANNER.']';
                        ?>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_left}}" class="form-control @error($settings_position_left) is-invalid @enderror"
                               name="{{$settings_position_left}}" value="{{ $promotion->settings[Promotion::SETTINGS_POSITION_LEFT] }}" placeholder="-=Отступ текста слева=-">
                        <label for="{{$settings_position_left}}">-=Отступ текста слева=-</label>
                        @error($settings_position_left)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_right}}" class="form-control @error($settings_position_right) is-invalid @enderror"
                               name="{{$settings_position_right}}" value="{{ $promotion->settings[Promotion::SETTINGS_POSITION_RIGHT] }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_right}}">-=Отступ текста справа=-</label>
                        @error($settings_position_right)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_top}}" class="form-control @error($settings_position_top) is-invalid @enderror"
                               name="{{$settings_position_top}}" value="{{ $promotion->settings[Promotion::SETTINGS_POSITION_TOP] }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_top}}">-=Отступ текста сверху=-</label>
                        @error($settings_position_top)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_bottom}}" class="form-control @error($settings_position_bottom) is-invalid @enderror"
                               name="{{$settings_position_bottom}}" value="{{ $promotion->settings[Promotion::SETTINGS_POSITION_BOTTOM] }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_bottom}}">-=Отступ текста снизу=-</label>
                        @error($settings_position_bottom)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_width_text_block}}" class="form-control @error($settings_width_text_block) is-invalid @enderror"
                               name="{{$settings_width_text_block}}" value="{{ $promotion->settings[Promotion::SETTINGS_WIDTH_TEXT_BLOCK] }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_width_text_block}}">-=Ширина текстового блока=-</label>
                        @error($settings_width_text_block)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3 datepicker-item">
                        <input type="text" class="datepicker-native form-control @error("expiration_start") is-invalid @enderror" name="expiration_start" id="expirationStart" value="@if($promotion->expiration_start){{ $promotion->expiration_start->isoFormat('D.M.Y') }}@endif" placeholder="-=Дата начала действия акции=-" autocomplete="off">
                        <label for="expirationStart">-=Дата начала действия акции=-</label>
                        @error("expiration_start")<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3 datepicker-item">
                        <input type="text" class="datepicker-native form-control @error("expiration_end") is-invalid @enderror" name="expiration_end" id="expirationEnd" value="@if($promotion->expiration_end){{ $promotion->expiration_end->isoFormat('D.M.Y') }}@endif" placeholder="-=Дата конца действия акции=-" autocomplete="off">
                        <label for="expirationEnd">-=Дата конца действия акции=-</label>
                        @error("expiration_end")<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <div class="form-check p-0">
                            <input type="checkbox" name="{{$settings_is_main_banner}}" id="{{$settings_is_main_banner}}" {{ isset($promotion->settings[Promotion::SETTINGS_IS_MAIN_BANNER]) ? 'checked' : '' }} value="1">
                            <label for="{{$settings_is_main_banner}}" class="form-check-label">Показать в баннере на главной</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="p-3 mb-3 bg-light border">
        <up-images data-destination="category">
            <label for="photo" class="form-label">-=Изображения акции=-</label>
            @if($promotion->photo)
                <div class="images-container">
                    <div class="image-item">
                        <div class="wrapper-image" data-photo-id="{{ $promotion->photo->id }}" data-photo-owner="promotion" data-promotion-id="{{ $promotion->id }}">
                            <img src="{{ asset($promotion->photo->getPhoto('small')) }}" alt="{{ $promotion->photo['alt'] }}">
                        </div>
                        <div class="image-control btn-group">
                            <form action="{{ route('admin.shop.promotion.photo.remove', [$promotion, $promotion->photo]) }}" method="POST" class="btn btn-danger">
                                @csrf
                                <button type="submit" class="btn p-0 text-white d-flex js-confirm">
                                    <span data-feather="x-circle"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            <input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="photo" form="promotionForm">
            @error('photo')<span class="invalid-feedback">{{ $message }}</span> @enderror
        </up-images>
    </div>

    <div class="p-3 mb-3 bg-light border">
        <button type="submit" class="btn btn-success w-100" form="promotionForm">Сохранить</button>
    </div>
@endsection
