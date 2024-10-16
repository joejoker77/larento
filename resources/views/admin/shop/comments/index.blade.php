@php
    /** @var $comment App\Entities\Shop\Comment */

use App\Entities\Shop\Comment;

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.admin')

@section('content')
    <div class="py-4 d-flex">
        <div class="h1">Комментарии</div>
        <div class="ms-auto">
            <form class="p-0 m-0" method="POST" id="formActions" action="{{ route('admin.shop.commentaries.set-status') }}">
                @csrf
                <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="published" class="btn btn-lg btn-primary" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Опубликовать">
                        <span data-feather="eye"></span>
                    </button>
                </div> |
                <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="remove" class="btn btn-lg btn-danger js-confirm" data-confirm="multi" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                            data-bs-title="Удалить комментарии">
                        <span data-feather="trash-2"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-striped" id="itemsTable">
        <thead>
        <tr>
            <th><input type="checkbox" class="form-check-input" name="select-all" style="cursor: pointer"></th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'id' ? '-id' : 'id']) }}">
                    ID @if(request('sort') && request('sort') == 'id') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-id') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'product' ? '-product' : 'product']) }}">
                    Продукт @if(request('sort') && request('sort') == 'product') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-product') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'status' ? '-status' : 'status']) }}">
                    Статус @if(request('sort') && request('sort') == 'status') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-status') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Имя пользователя</th>
            <th>Текст комментария</th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'vote' ? '-vote' : 'vote']) }}">
                    Оценка @if(request('sort') && request('sort') == 'vote') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-vote') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'created_at' ? '-created_at' : 'created_at']) }}">
                    Дата добавления @if(request('sort') && request('sort') == 'created_at') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-created_at') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Действия</th>
        </tr>
        <tr>
            <form action="{{route('admin.shop.commentaries.index')}}" name="search-comments" method="GET" id="searchItems"></form>
            <td style="width: 20px;">&nbsp;</td>
            <td style="width: 80px"><input type="text" form="searchItems" name="id" class="form-control" aria-label="Искать по ID" value="{{ request('id') }}"></td>
            <td style="width: 180px;"><input type="text" form="searchItems" name="product" class="form-control" aria-label="Искать по имени продукта" value="{{ request('product') }}"></td>
            <td style="width: 200px">
                <select name="status" form="searchItems" class="form-control js-choices" data-placeholder="Фильтр по статусу" aria-label="фильтр по статусу">
                    <option value=""></option>
                    @foreach(Comment::statusList() as $value => $name)
                        <option value="{{ $value }}" @selected(request('status') == $value)>{{ $name }}</option>
                    @endforeach
                </select>
            </td>
            <td style="width: 180px">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width: 180px">
                <select name="vote" form="searchItems" class="form-control js-choices" data-placeholder="Фильтр по оценке" aria-label="фильтр по оценке">
                    <option value=""></option>
                    <option value="1" @selected(request('vote') == '1')>Одна звезда</option>
                    <option value="2" @selected(request('vote') == '2')>Две звезды</option>
                    <option value="3" @selected(request('vote') == '3')>Три звезды</option>
                    <option value="4" @selected(request('vote') == '4')>Четыре звезды</option>
                    <option value="5" @selected(request('vote') == '5')>Пять звезд</option>
                </select>
            </td>
            <td style="width: 300px">
                <div class="datepicker-item-range d-flex justify-content-between flex-nowrap">
                    <input type="text" class="datepicker-native form-control" name="created_start" id="expirationStart" value="{{ request('created_start') }}" placeholder="-=От даты=-" autocomplete="off">
                    <span>до</span>
                    <input type="text" class="datepicker-native form-control" name="created_end" id="expirationEnd" value="{{ request('created_end') }}" placeholder="-=даты=-" autocomplete="off">
                </div>
            </td>
            <td style="width: 120px">&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        @foreach($commentaries as $comment)
            <tr>
                <td><input form="formActions" type="checkbox" value="{{ $comment->id }}" class="form-check-input" name="selected[]" style="cursor: pointer"></td>
                <td style="text-align: center">{{ $comment->id }}</td>
                <td style="text-align: center"><a href="{{ route('admin.shop.products.show', $comment->product) }}" target="_blank">{{ $comment->product->name }}</a></td>
                <td style="text-align: center"><span class="badge {{ Comment::statusLabel($comment->status) }}">{{ Comment::statusName($comment->status) }}</span></td>
                <td style="text-align: center">{{ $comment->user_name }}</td>
                <td>{{ $comment->comment }}</td>
                <td style="text-align: center">{{ $comment->vote }}</td>
                <td>{{ $intlFormatter->format(new DateTime($comment->created_at)) }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.shop.commentaries.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="@if($comment->status == $comment::STATUS_ACTIVE) Опубликованные комментарии можно только удалить @else Опубликовать @endif">
                        @csrf
                        <input type="hidden" name="action" value=@if($comment->status == $comment::STATUS_ACTIVE)"un-published"@elseif($comment->status == $comment::STATUS_DRAFT)"published"@endif">
                        <input type="hidden" name="selected[]" value="{{ $comment->id }}">
                        <button class="btn p-0 align-baseline @if($comment->status == $comment::STATUS_DRAFT) text-primary @else text-secondary @endif" type="submit" @if($comment->status == $comment::STATUS_ACTIVE) disabled @endif>
                            @if($comment->status == $comment::STATUS_ACTIVE)
                                <span data-feather="eye-off"></span>
                            @else
                                <span data-feather="eye"></span>
                            @endif
                        </button>
                    </form>|<form method="POST" class="list-inline-item js-confirm ms-2"
                      action="{{ route('admin.shop.commentaries.destroy', $comment) }}"
                      data-bs-toggle="tooltip" data-bs-placement="bottom"
                      data-bs-title="Удалить комментарий"
                    >
                        @csrf
                        @method('DELETE')
                        <button class="btn p-0 align-baseline js-confirm text-danger" type="submit">
                            <span data-feather="trash-2"></span>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $commentaries->appends(request()->input())->links() }}
@endsection

