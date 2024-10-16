<?php /** @var App\Entities\Site\Promotions\Promotion[] $promotions */ ?>
@extends('layouts.admin')
@section('content')
    <div class="py-4 d-flex">
        <a href="{{ route('admin.shop.promotion.create') }}" class="btn btn-success">Добавить Акцию</a>
        <div class="ms-auto">
            <form class="p-0 m-0" method="POST" id="formActions" action="{{ route('admin.shop.promotion.remove-batch') }}">
                @csrf
                <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="remove" class="btn btn-lg btn-danger js-confirm" data-confirm="multi" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                            data-bs-title="Удалить Акции">
                        <span data-feather="trash-2"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-striped" id="itemsTable">
        <thead>
        <tr>
            <th style="text-align: center">
                <input type="checkbox" class="form-check-input" name="select-all" style="cursor: pointer">
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'id' ? '-id' : 'id']) }}">
                    ID @if(request('sort') && request('sort') == 'id') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-id') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Изображение</th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'name' ? '-name' : 'name']) }}">
                    Наименование @if(request('sort') && request('sort') == 'name') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-name') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'title' ? '-title' : 'title']) }}">
                    Заголовок @if(request('sort') && request('sort') == 'title') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-title') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Срок действия</th>
            <th>Действия</th>
        </tr>
        <tr>
            <form action="?" name="search-promotions" method="GET" id="searchItems"></form>
            <td>&nbsp;</td>
            <td style="max-width: 50px;text-align: center">
                <input form="searchItems" type="text" name="id" class="form-control" aria-label="Искать по ID" value="{{ request('id') }}">
            </td>
            <td>&nbsp;</td>
            <td style="max-width: 175px">
                <input type="text" form="searchItems" name="name" class="form-control" aria-label="Искать по имени" value="{{ request('name') }}">
            </td>
            <td style="max-width: 175px">
                <input type="text" form="searchItems" name="title" class="form-control" aria-label="Искать по заголовку" value="{{ request('title') }}">
            </td>
            <td style="max-width: 175px">
                <div class="datepicker-item-range d-flex justify-content-between flex-nowrap">
                    <input type="text" class="datepicker-native form-control" name="expiration_start" id="expirationStart" value="{{ request('expiration_start') }}" placeholder="-=Дата начала=-" autocomplete="off">
                    <span>до</span>
                    <input type="text" class="datepicker-native form-control" name="expiration_end" id="expirationEnd" value="{{ request('expiration_end') }}" placeholder="-=Дата конца=-" autocomplete="off">
                </div>
            </td>
            <td style="width: 80px">&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        @foreach($promotions as $promotion)
            @php $badge = $promotion->statusBadge() @endphp
            <tr>
                <td style="text-align: center">
                    <input form="formActions" type="checkbox" value="{{ $promotion->id }}" class="form-check-input" name="selected[]" style="cursor: pointer">
                </td>
                <td style="text-align: center">{{ $promotion->id }}</td>
                <td style="width: 100px;text-align: center">
                    @if($promotion->photo)
                        <img src="{{ asset($promotion->photo->getPhoto('small')) }}" alt="{{ $promotion->photo->alt_tag }}" height="40">
                    @else
                        <i data-feather="camera-off"></i>
                    @endif
                </td>
                <td>{{ $promotion->name }}</td>
                <td style="white-space: nowrap">{{ $promotion->title }}</td>
                <td style="text-align: center;">
                    <div class="d-flex justify-content-between flex-nowrap gap-1">
                        <span style="line-height: 1.4;flex-grow: 1;flex-basis: 0;">@if($promotion->expiration_start) {{ Carbon\Carbon::parse($promotion->expiration_start)->format('d.m.Y') }} @endif</span>
                        <span style="line-height: 1.4;flex-grow: 1;flex-basis: 0;" class="badge {{ $badge['class'] }}">{{ $badge['text'] }}</span>
                        <span style="line-height: 1.4;flex-grow: 1;flex-basis: 0;">@if($promotion->expiration_end) {{ Carbon\Carbon::parse($promotion->expiration_end)->format('d.m.Y') }} @endif</span>
                    </div>
                </td>
                <td style="white-space: nowrap">
                    <a href="{{ route('admin.shop.promotion.edit', $promotion) }}" class="list-inline-item mx-1"
                       id="editCategory" data-bs-toggle="tooltip"
                       data-bs-placement="bottom"
                       data-bs-title="Редактировать"
                    >
                        <span data-feather="edit"></span>
                    </a>|<form method="POST" class="list-inline-item js-confirm ms-2"
                          action="{{ route('admin.shop.promotion.destroy', $promotion) }}"
                          data-bs-toggle="tooltip" data-bs-placement="bottom"
                          data-bs-title="Удалить акцию"
                    >
                        @csrf
                        @method('DELETE')
                        <button class="btn p-0 align-baseline js-confirm text-danger" type="submit"><span data-feather="trash-2"></span></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $promotions->appends(request()->input())->links() }}
@endsection
