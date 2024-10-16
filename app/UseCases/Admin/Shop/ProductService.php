<?php

namespace App\UseCases\Admin\Shop;


use Throwable;
use App\Entities\Shop\Value;
use Illuminate\Http\Request;
use App\Entities\Shop\Product;
use App\Entities\Shop\Attribute;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Shop\ProductRequest;

class ProductService
{

    /**
     * @throws Throwable
     */
    public function create(ProductRequest $request):Product
    {
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name'              => $request['name'],
                'sku'               => $request['sku'],
                'description'       => $request['description'],
                'meta'              => $request['meta'],
                'category_id'       => $request['category_id'],
                'status'            => Product::STATUS_DRAFT
            ]);

            if ($request->get('product_attributes')) {
                $this->assignAttributes($request->get('product_attributes'), $product);
            }

            if ($request->get('product_categories')) {
                $product->categories()->attach($request->get('product_categories'));
            }

            if ($request->get('product_tags')) {
                $product->tags()->attach($request->get('product_tags'));
            }

            $this->checkImages($request, $product);

            DB::commit();
            return $product;
        } catch (\PDOException $e) {
            DB::rollBack();
            report($e);
            throw new \DomainException('Сохранение сущности Product завершилось с ошибкой. Подробности: ' . $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function update(ProductRequest $request, Product $product): void
    {
        DB::beginTransaction();
        try {
            $product->update($request->only([
                'name',
                'sku',
                'description',
                'meta',
                'category_id',
                'status'
            ]), [
                'name'              => $request['name'],
                'sku'               => $request['sku'],
                'description'       => $request['description'],
                'meta'              => $request['meta'],
                'category_id'       => $request['category_id'],
                'status'            => Product::STATUS_DRAFT
            ]);

            if ($request->get('product_attributes')) {
                $product->values()->delete();
                $this->assignAttributes($request->get('product_attributes'), $product);
            }

            if ($request->get('product_categories')) {
                $product->categories()->detach();
                $product->categories()->attach($request->get('product_categories'));
            }

            if ($request->get('product_tags')) {
                $product->tags()->detach();
                $product->tags()->attach($request->get('product_tags'));
            }

            $this->checkImages($request, $product);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \DomainException('Сохранение сущности Product завершилось с ошибкой. Подробности: ' . $e->getMessage());
        }
    }

    public function setStatus(Request $request):string
    {
        $action = $request->get('action');
        $answer = '';
        if (empty($selected = $request->get('selected'))) {
            throw new \DomainException('Не выбран ни один товар');
        }
        $products = Product::find($selected);

        /** @var Product $product */
        foreach ($products as $product) {
            switch ($action) {
                case 'hit': $product->setHit(); $answer = 'Выбранные товары успешно добавлены в хиты продаж';break;
                case 'new': $product->setNew(); $answer = 'Выбранные товары успешно добавлены в новинки';break;
                case 'main-page': $product->setMainPage(); $answer = 'Выбранные товары успешно добавлены на главную страницу';break;
                case 'revoke-hit': $product->revokeHit(); $answer = 'Выбранные товары успешно удалены из хитов продаж';break;
                case 'revoke-new': $product->revokeNew(); $answer = 'Выбранные товары успешно удалены из новинок';break;
                case 'revoke-main-page': $product->revokeMainPage(); $answer = 'Выбранные товары успешно удалены с главной страницы';break;
                case 'published': $product->published(); $answer = 'Выбранные товары успешно опубликованы';break;
                case 'un-published': $product->unPublished(); $answer = 'Выбранные товары успешно сняты с бубликации';break;
                case 'remove': $product->delete(); $answer = 'Выбранные товары успешно удалены'; break;
            }
        }
        return $answer;
    }

    /**
     * @param array $attributes
     * @param Product $product
     * @return void
     */
    private function assignAttributes(array $attributes, Product $product):void
    {
        $attributes = array_filter($attributes);

        foreach ($attributes as $id => $options) {
            if ($attribute = Attribute::find($id)) {

                if ($attribute->mode == Attribute::MODE_SIMPLE) {
                    if (!Value::where('attribute_id', $id)->where('product_id', $product->id)->where('value', $options)->first()) {
                        $product->values()->create([
                            'attribute_id' => $id,
                            'value' => is_array($options) ? $options[0] : $options
                        ]);
                    }
                } elseif ($attribute->mode == Attribute::MODE_MULTIPLE) {
                    if (!Value::where('attribute_id', $id)->where('product_id', $product->id)->where('value', is_array($options) ? trim(implode(', ', $options), ', ') : $options)->first()) {
                        $product->values()->create([
                            'attribute_id' => $id,
                            'value' => is_array($options) ? trim(implode(', ', $options), ', '):$options
                        ]);
                    }
                }
            }
        }
    }

    private function checkImages(ProductRequest $request, Product $product): void
    {
        if ($images = $request->file('photo')) {
            foreach ($images as $i => $image) {
                if ($product->photos()->where("name", '=', str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName()) . '.webp')->first()) {
                    continue;
                }
                $photo = $product->photos()->create([
                    "name" => str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName()) . '.webp',
                    "sort" => $i,
                    "path" => Product::getImageParams()['path'] . $product->id . '/images/'
                ]);
                save_image_to_disk($image, $photo, Product::getImageParams()['sizes'], true);
            }
        }
    }

    public function searchVariant(string $query) : array
    {
        $result = [];
        $q      = Product::query();

        foreach (Product::$searchable as $field) {
            $q->orWhere($field, 'LIKE', '%'.$query.'%');
        }
        $results = $q->select(['id', 'name'])->take(10)->get();

        if (!$results->isEmpty()) {
            foreach ($results as $answerItem) {
                $result[] = ['name' => $answerItem->name, 'id' => $answerItem->id];
            }
        }
        return $result;
    }
}
