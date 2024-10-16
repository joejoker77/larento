<?php
    /** @var $promotion Promotion */
use \App\Entities\Site\Promotions\Promotion;
?>
@extends('layouts.admin')
@section('content')
    <form method="POST" id="productForm" action="{{ route('admin.shop.promotion.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row mt-4">
            <h3 class="mb-4 pb-4 border-bottom">Создание новой акции/баннера</h3>
            <div class="col-md-9 base-form">
                <div class="p-3 mb-3 bg-light border ''">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" placeholder="-=Наименование акции=-" required>
                                <label for="name">-=Наименование акции=-</label>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                                       name="title" value="{{ old('title') }}" placeholder="-=Заголовок акции=-">
                                <label for="title">-=Заголовок акции=-</label>
                                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border ''">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Текст акции (слайд)</h6>
                            <div class="form-text">
                                <textarea id="description" name="description" aria-label="-=Описание акции=-"
                                          class="ckeditor form-control @error('description') is-invalid @enderror"
                                          placeholder="-=Описание акции=-" rows="7">{{ trim(old('description')) }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Текст акции (дополнительно слайд)</h6>
                            <div class="form-text">
                                <textarea id="description_two" name="description_two" aria-label="-=Описание акции (дополнительно)=-"
                                          class="ckeditor form-control @error('description_two') is-invalid @enderror"
                                          placeholder="-=Описание акции (дополнительно)=-" rows="7">{{ trim(old('description_two')) }}</textarea>
                                @error('description_two')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <h6>Текст акции (описание)</h6>
                        <div class="form-text">
                                <textarea id="description_three" name="description_three" aria-label="-=Текст акции (описание)=-"
                                          class="ckeditor form-control @error('description_three') is-invalid @enderror"
                                          placeholder="-=Текст акции (описание)=-" rows="7">{{ trim(old('description_three')) }}</textarea>
                            @error('description_three')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12">
                            @include('layouts.partials.meta', ['meta'=> old('meta'), 'required' => false])
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="p-3 mb-3 bg-light border ''">
                        <up-images data-destination="category">
                            <label for="photo" class="form-label">-=Изображения акции=-</label>
                            <div class="images-container"></div>
                            <input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="photo">
                            @error('photo')<span class="invalid-feedback">{{ $message }}</span> @enderror
                        </up-images>
                    </div>
                </div>
                <div class="p-3 mb-3 bg-light border ''">
                    <button type="submit" class="btn btn-success w-100">Сохранить</button>
                </div>
            </div>
            <div class="col-md-3 adding-forms">
                <div class="p-3 mb-3 bg-light border ''">
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
                               name="{{$settings_position_left}}" value="{{ old($settings_position_left) }}" placeholder="-=Отступ текста слева=-">
                        <label for="{{$settings_position_left}}">-=Отступ текста слева=-</label>
                        @error($settings_position_left)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_right}}" class="form-control @error($settings_position_right) is-invalid @enderror"
                               name="{{$settings_position_right}}" value="{{ old($settings_position_right) }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_right}}">-=Отступ текста справа=-</label>
                        @error($settings_position_right)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_top}}" class="form-control @error($settings_position_top) is-invalid @enderror"
                               name="{{$settings_position_top}}" value="{{ old($settings_position_top) }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_top}}">-=Отступ текста сверху=-</label>
                        @error($settings_position_top)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_position_bottom}}" class="form-control @error($settings_position_bottom) is-invalid @enderror"
                               name="{{$settings_position_bottom}}" value="{{ old($settings_position_bottom) }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_position_bottom}}">-=Отступ текста снизу=-</label>
                        @error($settings_position_bottom)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="{{$settings_width_text_block}}" class="form-control @error($settings_width_text_block) is-invalid @enderror"
                               name="{{$settings_width_text_block}}" value="{{ old($settings_width_text_block) }}" placeholder="-=Отступ текста справа=-">
                        <label for="{{$settings_width_text_block}}">-=Ширина текстового блока=-</label>
                        @error($settings_width_text_block)<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3 datepicker-item">
                        <input type="text" class="datepicker-native form-control @error("expiration_start") is-invalid @enderror" name="expiration_start" id="expirationStart" value="{{ old("expiration_start") }}" placeholder="-=Дата начала действия акции=-" autocomplete="false">
                        <label for="expirationStart">-=Дата начала действия акции=-</label>
                        @error("expiration_start")<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3 datepicker-item">
                        <input type="text" class="datepicker-native form-control @error("expiration_end") is-invalid @enderror" name="expiration_end" id="expirationEnd" value="{{ old("expiration_end") }}" placeholder="-=Дата конца действия акции=-" autocomplete="false">
                        <label for="expirationEnd">-=Дата конца действия акции=-</label>
                        @error("expiration_end")<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <div class="form-check p-0">
                            <input type="checkbox" name="{{$settings_is_main_banner}}" id="{{$settings_is_main_banner}}" {{ old($settings_is_main_banner) ? 'checked' : '' }} value="1">
                            <label for="{{$settings_is_main_banner}}" class="form-check-label">Показать в баннере на главной</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
