<?php

namespace App\Http\Controllers\Catalog;


use App\Events\NewOrder;
use App\UseCases\Shop\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{

    private OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function sendOrder(Request $request):JsonResponse|RedirectResponse
    {
        $order = $this->service->create($request);

        NewOrder::dispatch($order);

        return $request->ajax() ?
            response()->json($order) :
            back()->with('success', 'Спасибо за вашу заявку. Наши менеджеры свяжутся с вами в ближайшее время.');
    }
}
