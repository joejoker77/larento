@php
/** @var $settings Settings */


use App\Entities\Site\Settings;
$names = Settings::names();
@endphp

@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Статистика</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar" class="align-text-bottom"></span>
                This week
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between flex-nowrap mb-3 border-bottom">
        <div class="col" style="min-height: 300px">
            <canvas class="my-4 w-100" id="myChart" aria-label="Hello Statistics"></canvas>
        </div>
        <div class="col" style="min-height: 300px"></div>
        <div class="col" style="min-height: 300px"></div>
        <div class="col" style="min-height: 300px"></div>
    </div>



    <form method="POST" action="{{ route('admin.home.update') }}">
        @csrf
        <div class="row mt-4">
            <h3 class="mb-4 pb-4 border-bottom">Настройки сайта</h3>
            <div class="col-md-6">
                <div class="p-3 mb-3 bg-light border">
                    <h4>Основные</h4>
                    <div class="form-floating mb-3">
                        <input id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off"
                               name="name" value="{{ old('name', $settings) }}" type="text" placeholder="{{ $names['name'] }}">
                        <label for="name" class="form-label">{{$names['name']}}</label>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="slogan" class="form-control @error('slogan') is-invalid @enderror" autocomplete="off"
                               name="slogan" value="{{ old('slogan', $settings) }}" type="text" placeholder="{{$names['slogan']}}">
                        <label for="slogan" class="form-label">{{ $names['slogan'] }}</label>
                        @error('slogan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="pagination" class="form-control @error('default_pagination') is-invalid @enderror" autocomplete="off"
                               name="default_pagination" value="{{ old('default_pagination', $settings) }}" type="number" min="1" max="15" step="1" placeholder="{{$names['default_pagination']}}">
                        <label for="pagination" class="form-label">{{ $names['default_pagination'] }}</label>
                        @error('default_pagination')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="work_time" class="form-control @error('work_time') is-invalid @enderror" autocomplete="off"
                               name="work_time" value="{{ old('work_time', $settings) }}" type="text" placeholder="{{$names['work_time']}}">
                        <label for="work_time" class="form-label">{{ $names['work_time'] }}</label>
                        @error('work_time')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating">
                        <input id="address" class="form-control @error('address') is-invalid @enderror" autocomplete="off"
                               name="address" value="{{ old('address', $settings) }}" type="text" placeholder="{{$names['address']}}">
                        <label for="address" class="form-label">{{ $names['address'] }}</label>
                        @error('address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 mb-3 bg-light border">
                    <h4>Контакты компании</h4>
                    <div class="form-floating mb-3">
                        <textarea name="emails" id="emails" rows="6" placeholder="{{$names['emails']}}" class="form-control h-auto @error('emails') is-invalid @enderror">{{ old('emails', $emails) }}</textarea>
                        <label for="emails" class="form-label">{{$names['emails']}}</label>
                        @error('emails')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="phones" id="phones" rows="6" placeholder="{{$names['phones']}}" class="form-control h-auto @error('phones') is-invalid @enderror">{{ old('phones', $phones) }}</textarea>
                        <label for="phones" class="form-label">{{$names['phones']}}</label>
                        @error('phones')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="p-3 mb-3 bg-light border">
                    <h4>Настройки домашней страницы</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input id="mainHead" class="form-control @error('main_head') is-invalid @enderror" autocomplete="off"
                                       name="main_head" value="{{ old('main_head', $settings) }}" type="text" placeholder="{{ $names['main_head'] }}">
                                <label for="mainHead" class="form-label">{{$names['main_head']}}</label>
                                @error('main_head')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input id="quantityOffer" class="form-control @error('quantity_offer') is-invalid @enderror" autocomplete="off"
                                       name="quantity_offer" value="{{ old('quantity_offer', $settings) }}" type="number" min="1" max="12" step="1" placeholder="{{ $names['quantity_offer'] }}">
                                <label for="quantityOffer" class="form-label">{{$names['quantity_offer']}}</label>
                                @error('quantity_offer')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mainText" class="form-label">{{$names['main_text']}}</label>
                        <textarea name="main_text" id="mainText" rows="6" placeholder="{{$names['main_text']}}" class="form-control ckeditor @error('main_text') is-invalid @enderror">{{ old('main_text', $settings) }}</textarea>
                        @error('main_text')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 mb-3 bg-light border">
            <button type="submit" class="btn btn-success w-100">Сохранить</button>
        </div>
    </form>
@endsection
