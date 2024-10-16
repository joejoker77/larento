<?php

namespace App\Entities\Shop;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Traits\WithMediaGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property integer $id
 * @property string $sku
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property integer $category_id
 * @property string $status
 * @property float $volume
 * @property boolean $hit
 * @property boolean $new
 * @property boolean $display_home
 * @property float $rating
 * @property array $meta
 *

 * @property Category $category
 * @property Category[] $categories
 * @property Value[] $values
 * @property Product[] $related
 * @property Photo[] $photos
 * @property Comment[] $commentaries
 *
 * @method Builder active()
 * @method Builder canBuy()
 *
 */
class Product extends Model
{

    use HasFactory, WithMediaGallery;

    protected $table = 'shop_products';

    protected $fillable = [
        'name',
        'sku',
        'hit',
        'new',
        'description',
        'category_id',
        'status',
        'meta',
        'rating',
        'display_home'
    ];

    protected $casts = [
        'meta' => 'array',
        'hit' => 'boolean',
        'new' => 'boolean',
        'display_home' => 'boolean'
    ];

    public static $searchable = ['name', 'sku', 'description'];

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_NEW = 'new';
    const STATUS_HIT = 'hit';

    public const IMAGE_SIZE_SMALL = 105;

    public const IMAGE_SIZE_THUMB = 338;

    public const IMAGE_SIZE_MEDIUM = 685;

    public const IMAGE_SIZE_LARGE = 1024;

    public const IMAGE_SIZE_FULL = 1980;

    private const IMAGE_SIZES = [
        'small', 'thumb', 'medium', 'large', 'full'
    ];

    public const IMAGE_PATH = 'files/products/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::saved(function ($model) {
            $category = $model->category;
            Cache::forget('products_search_'.$category->id);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function statusList(): array
    {
        return [
            self::STATUS_DRAFT  => 'Черновик',
            self::STATUS_ACTIVE => 'Опубликованный',
            self::STATUS_HIT    => 'Лидер продаж',
            self::STATUS_NEW    => 'Новинка'
        ];
    }

    public static function productStatuses(): array
    {
        return [
            self::STATUS_DRAFT  => 'Черновик',
            self::STATUS_ACTIVE => 'Опубликованный'
        ];
    }

    public static function statusLabel($status): string
    {
        return match ($status) {
            self::STATUS_DRAFT => 'text-bg-secondary',
            self::STATUS_ACTIVE => 'text-bg-success',
            self::STATUS_NEW => 'text-bg-info',
            self::STATUS_HIT => 'text-bg-warning',
            default => 'text-bg-light',
        };
    }

    public static function statusName($status): string
    {
        return self::statusList()[$status];
    }

    public static function getImageParams():array
    {
        return [
            'sizes' => [
                'small'  => self::IMAGE_SIZE_SMALL,
                'thumb'  => self::IMAGE_SIZE_THUMB,
                'medium' => self::IMAGE_SIZE_MEDIUM,
                'large'  => self::IMAGE_SIZE_LARGE,
                'full'   => self::IMAGE_SIZE_FULL,
            ],
            'path' => self::IMAGE_PATH
        ];
    }

    public function getMainImage($size, $index = 0):null|string
    {
        /** @var Photo $photo */
        $photos = $this->photos->toArray();
        if (!empty($photos[$index])) {
            $photo = $photos[$index];
            return 'storage/' . $photo['path'] . $size . '_' . $photo['name'];
        }
        return null;
    }

    public function isActive():bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isDraft():bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isNew():bool
    {
        return $this->new;
    }

    public function isHit():bool
    {
        return $this->hit;
    }

    public function setHit():void
    {
        $this->update(['hit'=>1]);
    }

    public function revokeHit():void
    {
        $this->update(['hit'=>0]);
    }

    public function setNew():void
    {
        $this->update(['new' => 1]);
    }

    public function revokeNew():void
    {
        $this->update(['new'=>0]);
    }

    public function setMainPage():void
    {
        $this->update(['display_home' => 1]);
    }

    public function revokeMainPage():void
    {
        $this->update(['display_home'=>0]);
    }

    public function published():void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function unPublished():void
    {
        $this->update(['status' => self::STATUS_DRAFT]);
    }

    public function setRating(): static
    {
        $amount   = 0;
        $total    = 0;
        foreach ($this->commentaries()->get() as $comment) {
            $amount++;
            $total += $comment->getRating();
        }
        $this->rating = $this->roundToFraction(round($amount ? $total / $amount : 0, 2), 4);
        return $this;
    }

    public function category():HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'shop_category_product', 'product_id', 'category_id');
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'shop_product_tag', 'product_id', 'tag_id');
    }

    public function values():HasMany
    {
        return $this->hasMany(Value::class, 'product_id', 'id')->with(['attribute', 'product.photos']);
    }

    public function photos():BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'shop_products_photos','product_id', 'photo_id')->orderBy('sort');
    }

    public function related():BelongsToMany
    {
        return $this->belongsToMany(self::class, 'shop_related','product_id', 'related_id')->with(['photos','category']);
    }

    public function commentaries():HasMany
    {
        return $this->hasMany(Comment::class, 'product_id', 'id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter): Builder
    {
        return $filter->apply($builder);
    }

    private function roundToFraction($number, $denominator = 1): float|int
    {
        $x = $number * $denominator;
        $x = $x > 20 ? floor($x) : ceil($x);
        $x = $x / $denominator;
        return $x;
    }
}

