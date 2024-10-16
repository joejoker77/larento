@php
/** @var $orders App\Entities\Shop\Order[] */
use App\Entities\Shop\Status;
use App\Entities\Shop\Order;
@endphp
@extends('layouts.admin')

@section('content')
    <div class="py-4 d-flex">
        <div class="h1">Заявки</div>
        <div class="ms-auto">
            <form class="p-0 m-0" method="POST" id="formActions" action="{{ route('admin.shop.orders.set-status') }}">
                @csrf
                <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="{{Status::PAID}}" class="btn btn-lg btn-warning" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Установить статус оплачен">
                        <span data-feather="dollar-sign"></span>
                    </button>
                </div> | <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="{{Status::PROGRESS}}" class="btn btn-lg btn-info" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Установить статус в работе">
                        <span data-feather="power"></span>
                    </button>
                </div> | <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="{{Status::COMPLETED}}" class="btn btn-lg btn-success" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Установить статус завершен">
                        <span data-feather="check"></span>
                    </button>
                </div> | <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="{{Status::CANCELLED}}" class="btn btn-lg btn-danger" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Установить статус отменен">
                        <span data-feather="x"></span>
                    </button>
                </div> | <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="{{Status::CANCELLED_BY_CUSTOMER}}" class="btn btn-lg btn-danger" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Установить статус отменен заказчиком">
                        <span data-feather="x-circle"></span>
                    </button>
                </div> |
                <div class="btn-group" role="group" aria-label="control buttons">
                    <button type="submit" name="action" value="remove" class="btn btn-lg btn-danger js-confirm" data-confirm="multi" style="line-height: 0"
                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                            data-bs-title="Удалить заявки">
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
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'subject' ? '-subject' : 'subject']) }}">
                    Тема заявки @if(request('sort') && request('sort') == 'subject') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-subject') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'product_name' ? '-product_name' : 'product_name']) }}">
                    Продукт @if(request('sort') && request('sort') == 'product_name') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-product_name') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Статус</th>
            <th>
                <a class="link-secondary" href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'user_name' ? '-user_name' : 'user_name']) }}">
                    Имя пользователя @if(request('sort') && request('sort') == 'user_name') <i data-feather="chevrons-up"></i> @endif
                    @if(request('sort') && request('sort') == '-user_name') <i data-feather="chevrons-down"></i> @endif
                </a>
            </th>
            <th>Телефон пользователя</th>
            <th>Заметка</th>
            <th>Действия</th>
        </tr>
        <tr>
            <form action="{{route('admin.shop.orders.index')}}" name="search-comments" method="GET" id="searchItems"></form>
            <td style="width: 20px;">&nbsp;</td>
            <td style="width: 80px"><input type="text" form="searchItems" name="id" class="form-control" aria-label="Искать по ID" value="{{ request('id') }}"></td>
            <td><input type="text" form="searchItems" name="subject" class="form-control" aria-label="Искать по теме заявки" value="{{ request('subject') }}"></td>
            <td style="width: 180px;"><input type="text" form="searchItems" name="product_name" class="form-control" aria-label="Искать по имени продукта" value="{{ request('product_name') }}"></td>
            <td style="width: 200px">
                <select name="current_status" form="searchItems" class="form-control js-choices" data-placeholder="Фильтр по статусу" aria-label="фильтр по статусу">
                    <option value=""></option>
                    @foreach(Order::statusesList() as $value => $name)
                        <option value="{{ $value }}" @selected(request('current_status') == $value)>{{ $name }}</option>
                    @endforeach
                </select>
            </td>
            <td style="width: 180px"><input type="text" form="searchItems" name="user_name" class="form-control" aria-label="Искать по имени пользователя" value="{{ request('user_name') }}"></td>
            <td style="width: 180px"><input type="text" form="searchItems" name="user_phone" class="form-control" aria-label="Искать по номеру телефона" value="{{ request('user_phone') }}"></td>
            <td>&nbsp;</td>
            <td style="width: 210px">&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td><input form="formActions" type="checkbox" value="{{ $order->id }}" class="form-check-input" name="selected[]" style="cursor: pointer"></td>
                <td style="text-align: center">{{ $order->id }}</td>
                <td style="text-align: center"><a href="{{ route('admin.shop.orders.show', $order) }}">{{ $order->subject }}</a></td>
                <td style="text-align: center">{{ $order->product_name }}</td>
                <td style="text-align: center"><span class="badge {{ Order::statusLabel($order->current_status) }}">{{ Order::getStatus($order->current_status) }}</span></td>
                <td style="text-align: center">{{ $order->user_name }}</td>
                <td>{{$order->user_phone}}</td>
                <td>{!! $order->note !!}</td>
                <td>
                    <form method="POST" action="{{ route('admin.shop.orders.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="Установить статус оплачен">
                        @csrf
                        <input type="hidden" name="action" value="{{Status::PAID}}">
                        <input type="hidden" name="selected[]" value="{{ $order->id }}">
                        <button class="btn p-0 align-baseline text-primary" type="submit"><span data-feather="dollar-sign"></span></button>
                    </form>|<form method="POST" action="{{ route('admin.shop.orders.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="Установить статус в работе">
                        @csrf
                        <input type="hidden" name="action" value="{{Status::PROGRESS}}">
                        <input type="hidden" name="selected[]" value="{{ $order->id }}">
                        <button class="btn p-0 align-baseline text-primary" type="submit"><span data-feather="power"></span></button>
                    </form>|<form method="POST" action="{{ route('admin.shop.orders.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="Установить статус завершен">
                        @csrf
                        <input type="hidden" name="action" value="{{Status::COMPLETED}}">
                        <input type="hidden" name="selected[]" value="{{ $order->id }}">
                        <button class="btn p-0 align-baseline text-primary" type="submit"><span data-feather="check"></span></button>
                    </form>|<form method="POST" action="{{ route('admin.shop.orders.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="Установить статус отменен">
                        @csrf
                        <input type="hidden" name="action" value="{{Status::CANCELLED}}">
                        <input type="hidden" name="selected[]" value="{{ $order->id }}">
                        <button class="btn p-0 align-baseline text-primary" type="submit"><span data-feather="x"></span></button>
                    </form>|<form method="POST" action="{{ route('admin.shop.orders.set-status') }}" class="list-inline-item mx-1" data-bs-placement="left"
                          data-bs-toggle="tooltip" data-bs-title="Установить статус отменен заказчиком">
                        @csrf
                        <input type="hidden" name="action" value="{{Status::CANCELLED_BY_CUSTOMER}}">
                        <input type="hidden" name="selected[]" value="{{ $order->id }}">
                        <button class="btn p-0 align-baseline text-primary" type="submit"><span data-feather="x-circle"></span></button>
                    </form>|
                    <form method="POST" class="list-inline-item js-confirm ms-2"
                                  action="{{ route('admin.shop.orders.destroy', $order) }}"
                                  data-bs-toggle="tooltip" data-bs-placement="bottom"
                                  data-bs-title="Удалить заявку"
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
    {{ $orders->appends(request()->input())->links() }}
@endsection
