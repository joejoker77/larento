<?php

namespace App\Http\Controllers\Admin;


use App\Entities\Blog\Post;
use Illuminate\Http\Request;
use App\Entities\Shop\Photo;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Entities\Site\Promotions\Promotion;
use App\Entities\Blog\Category as BlogCategory;

class PhotoController extends Controller
{


    public function getPhotos(Request $request): JsonResponse|View
    {
        /** @var Photo $photo */
        try {
            $object = match ($request['owner']) {
                'category'      => Category::find($request['category_id']),
                "product"       => Product::find($request['product_id']),
                'promotion'     => Promotion::find($request['promotion_id']),
                'post'          => Post::find($request['post_id']),
                'blog-category' => BlogCategory::find($request['category_id']),
                default => null
            };
            $photos = $object->photos ?? $object->photo()->get();

            if (!$photos) {
                return response()->json(['error' => 'Изображения не найдены']);
            }

            return view('admin.partials.photos', compact('photos'), ['photo_id' => $request['id']]);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'При обработке запроса произошла ошибка: '. $exception->getMessage()]);
        }

    }

    public function updatePhoto(Request $request): JsonResponse
    {
        try {
            $photo = Photo::find($request['id']);
            $photo->description = $request['description'];
            $photo->alt_tag = $request['alt_tag'];
            $photo->save();
            return response()->json(['success' => 'Объект Photo успешно обновлен']);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Не удалось обновить объект Photo. '.$exception->getMessage()]);
        }
    }
}
