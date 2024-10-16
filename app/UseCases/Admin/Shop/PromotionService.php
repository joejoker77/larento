<?php

namespace App\UseCases\Admin\Shop;


use App\Entities\Shop\Photo;
use App\Events\ShopPromotionOnDelete;
use Illuminate\Support\Facades\DB;
use App\Entities\Site\Promotions\Promotion;
use App\Http\Requests\Admin\Shop\PromotionRequest;
use Illuminate\Support\Facades\Event;

class PromotionService
{
    public function create(PromotionRequest $request):Promotion
    {
        DB::beginTransaction();
        try {

            $promotion = Promotion::create([
                "name"             => $request->get('name'),
                "title"            => $request->get('title'),
                "description"      => $request->get('description'),
                "description_two"  => $request->get('description_two'),
                "description_three"  => $request->get('description_three'),
                "meta"             => $request->get('meta'),
                "settings"         => $request->get('settings'),
                "expiration_start" => $request->get('expiration_start'),
                "expiration_end"   => $request->get('expiration_end'),
            ]);

            $photo = $this->checkImages($request, $promotion);

            $promotion->photo()->associate($photo)->save();

            DB::commit();
            return $promotion;
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \DomainException('Сохранение акции завершилось с ошибкой. Подробности: ' . $e->getMessage());
        }
    }

    public function update(PromotionRequest $request, Promotion $promotion):void
    {
        DB::beginTransaction();
        try {
            $promotion->update($request->only([
                "name",
                "title",
                "description",
                "description_two",
                "description_three",
                "meta",
                "settings",
                "expiration_start",
                "expiration_end",
            ]), [
                $request->get('name'),
                $request->get('title'),
                $request->get('description'),
                $request->get('description_two'),
                $request->get('description_three'),
                $request->get('meta'),
                $request->get('settings'),
                $request->get('expiration_start'),
                $request->get('expiration_end'),
            ]);

            if ($request->exists('photo')) {
                $photo = $this->checkImages($request, $promotion);
                $promotion->photo()->associate($photo);
            }

            $promotion->save();

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            report($e);
            throw new \DomainException('Сохранение сущности Product завершилось с ошибкой. Подробности: ' . $e->getMessage());
        }
    }

    private function checkImages(PromotionRequest $request, Promotion $promotion): Photo|null
    {
        if ($image = $request->file('photo')) {
            $photo = $promotion->photo()->create([
                "name" => str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName()) . '.webp',
                "sort" => 0,
                "path" => Promotion::getImageParams()['path']
            ]);
            save_image_to_disk($image, $photo, Promotion::getImageParams()['sizes']);
            return $photo;
        }
        return null;
    }
}

