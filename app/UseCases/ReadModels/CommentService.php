<?php

namespace App\UseCases\ReadModels;

use App\Entities\Shop\Comment;
use App\Entities\Shop\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Site\CommentRequest;
use Symfony\Component\HttpFoundation\Cookie;

class CommentService
{
    public function sendComment(CommentRequest $request, Product $product):array
    {
        try {
            $cookieValue = $request->cookie('sc',);
            if(!$cookieValue) {
                $cookie = cookie('sc', json_encode([$product->id]), 525600);
            } else if (!in_array($product->id, json_decode($cookieValue))) {
                $arrayValue   = json_decode($cookieValue);
                $arrayValue[] = $product->id;
                $cookie = cookie('sc', json_encode($arrayValue), 525600);
            } else {
                throw new \DomainException('Комментарий не отправлен! Вы уже оставляли коментарий для этого товара');
            }

            DB::beginTransaction();
            /**
             * @var $comment Comment
             */
            $comment = Comment::make([
                'product_id' => $product->id,
                'status'     => Comment::STATUS_DRAFT,
                'user_name'  => $request->get('user_name'),
                'comment'    => $request->get('comment'),
                'vote'       => $request->get('vote')
            ]);

            $comment->save();
            $product->setRating()->save();

            DB::commit();

        } catch (\PDOException $exception) {
            DB::rollBack();
            throw new \DomainException('При сохранении комментария возникла ошибка: ' . $exception->getMessage());
        }
        return ['cookie' => $cookie, 'comment' => $comment];
    }
}
