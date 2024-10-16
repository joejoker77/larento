@php
    /** @var $order App\Entities\Shop\Order */

$intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y');
@endphp
@extends('layouts.admin')

@section('content')
    <div class="pt-4 d-flex mb-3">
        <h3 class="h4">Заявка № {{ $order->id }}</h3>
        <div class="ms-auto btn-group" role="group" aria-label="control buttons">
            <form class="btn btn-danger" method="POST" action="{{ route('admin.shop.orders.destroy', $order) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Удалить">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 text-white d-flex js-confirm" style="line-height: 0">
                    <span data-feather="trash-2"></span>
                </button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Статус</th>
                    <td>
                        <div class="d-flex flex-column gap-2">
                        @foreach($order::statusesList() as $status => $name)
                            @foreach($order->statuses as $st)
                                @php
                                    $val  = is_array($st) ? $st['value'] : $st;
                                    $date = is_array($st) ? $st['created_at'] : $order->created_at;
                                @endphp
                                @if($val == $status)
                                    <div class="d-flex flex-row flex-wrap justify-content-center">
                                        <div class="text-center col">
                                            <span class="badge {{ $order::statusLabel($status) }}">{{ $order::getStatus($status) }}</span>
                                        </div>
                                        <div class="col">
                                            {{$intlFormatter->format($date)}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Тема заявки</th>
                    <td>{{ $order->subject }}</td>
                </tr>
                <tr>
                    <th>Наименование продукта</th>
                    <td>{{ $order->product_name }}</td>
                </tr>
                <tr>
                    <th>Имя пользователя</th>
                    <td>{{ $order->user_name }}</td>
                </tr>
                <tr>
                    <th>Телефон пользователя</th>
                    <td>{{ $order->user_phone }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5>Добавить заметку к заявке</h5>
            <form method="POST" action="{{ route('admin.shop.orders.add-note', $order) }}">
                @csrf
                <div class="form-floating mb-5">
                    <textarea class="form-control ckeditor" name="note" id="note" placeholder="Заметка к заявке" aria-label="Заметка к заявке">{!! $order->note !!}</textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Сохранить заметку</button>
            </form>
        </div>
    </div>
@endsection
