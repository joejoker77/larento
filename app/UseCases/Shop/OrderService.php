<?php

namespace App\UseCases\Shop;


use Illuminate\Http\Request;
use App\Entities\Shop\Order;
use App\Entities\Shop\Status;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(Request $request):Order
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_name'      => $request->get('user_name'),
                'user_phone'     => $request->get('user_phone'),
                'subject'        => $request->get('subject'),
                'product_name'   => $request->get('product_name'),
                'statuses'       => [Status::NEW],
                'current_status' => Status::NEW
            ]);

            if ($kitchenData = $request->get('CalcForm')) {
                $type = match ($kitchenData['type']) {
                    '1' => 'прямая',
                    '2' => 'угловая',
                    '3' => 'п-образная',
                    '4' => 'с островом'
                };
                $material = match ($kitchenData['material']) {
                    '1' => 'ЛДСП',
                    '2' => 'МДФ',
                    '3' => 'Пластик',
                    '4' => 'ПВХ',
                    '5' => 'Эмаль',
                    '6' => 'Массив'
                };
                $size = $kitchenData['sizeA'].'м';

                if ($kitchenData['sizeB']) {
                    $size .= ' X '.$kitchenData['sizeB'].'м';
                }

                if ($kitchenData['sizeC']) {
                    $size .= ' X '.$kitchenData['sizeC'].'м';
                }
                $topRow      = $kitchenData['topRow'] == 'on' ? 'с верхним рядом' : 'без верхнего ряда';
                $order->note = 'Конфигурация кухни: <br>Тип - '.$type. '; <br>Материал - '.$material.';<br>Размер - '.$size.'<br>'.$topRow;
            }
            $order->saveOrFail();

            DB::commit();

            return $order;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \DomainException('При оформлении заявки произошла непредвиденная ошибка! '. $exception->getMessage());
        }
    }
}
