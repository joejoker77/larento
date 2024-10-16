<?php

namespace App\Entities\Shop;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property integer $id
 * @property integer $product_id
 * @property string $user_name
 * @property string $status
 * @property integer $vote
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Product[] $product
 *
 * @method Builder active()
 *
 */
class Comment extends Model
{
    protected $table = 'shop_comments';

    protected $fillable = [
        'product_id', 'user_name', 'status', 'vote', 'comment'
    ];

    public static $searchable = ['user_name'];

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';

    public static function statusList(): array
    {
        return [
            self::STATUS_DRAFT  => 'Ожидает модерации',
            self::STATUS_ACTIVE => 'Опубликованный',
        ];
    }

    public static function statusLabel($status): string
    {
        return match ($status) {
            self::STATUS_DRAFT  => 'text-bg-danger',
            self::STATUS_ACTIVE => 'text-bg-success',
            default => 'text-bg-light',
        };
    }

    public static function statusName($status): string
    {
        return self::statusList()[$status];
    }

    public function isActive():bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function product():HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getRating(): int
    {
        return $this->vote;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
