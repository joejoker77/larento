<?php

namespace App\Http\Controllers\Admin\Shop;


use Illuminate\View\View;
use App\Traits\QueryParams;
use App\Entities\Shop\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\UseCases\Admin\Shop\OrderService;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    use QueryParams;

    private OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request):View
    {
        $orders = Order::where(function (Builder $query) use($request) {
            $this->queryParams($request, $query);
        })->paginate(20, ['*'], 'orders');

        return \view("admin.shop.orders.index", compact('orders'));
    }

    public function setStatus(Request $request):RedirectResponse
    {   $ids = $request->get('selected');
        if (empty($ids)) {
            return back()->with('error', 'Не выбрана или не найдена ни одна заявка');
        }
        foreach ($ids as $id) {
            $order = Order::find($id);
            $request->get('action') == 'remove' ? $order->delete() : $order->addStatus($request->get('action'));
        }
        return back()->with('success', $request->get('action') == 'remove' ? 'Выбранные заказы успешно удалены' : 'Статус успешно изменен');
    }

    public function show(Order $order):View
    {
        return \view('admin.shop.orders.show', compact('order'));
    }

    public function addNote(Order $order, Request $request):RedirectResponse
    {
        $order->setNote($request->get('note'));
        return back()->with('success', 'Заметка успешно сохранена');
    }
}
