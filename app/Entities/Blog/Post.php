<?php

namespace App\Entities\Blog;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Entities\Shop\Photo;
use App\Traits\WithMediaGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $status
 * @property integer $sort
 * @property string $slug
 * @property array $meta
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Photo[] $photos
 * @property Category $category
 * @property Category[] $categories
 */
class Post extends Model implements Sitemapable
{
    use WithMediaGallery;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id', 'title', 'description', 'content', 'status', 'sort', 'meta', 'slug'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    public static $searchable = ['title', 'description', 'content'];


    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';

    public const IMAGE_SIZE_SMALL = 150;

    public const IMAGE_SIZE_THUMB = 250;

    public const IMAGE_SIZE_MEDIUM = 480;

    public const IMAGE_SIZE_LARGE = 1024;

    public const IMAGE_SIZE_FULL = 1980;

    private const IMAGE_SIZES = [
        'small', 'thumb', 'medium', 'large', 'full'
    ];

    public const IMAGE_PATH = 'files/blog/posts/';

    public static function boot()
    {
        parent::boot();

        Model::preventLazyLoading();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('catalog.index',post_path($this->category()->first(), $this)))
            ->setLastModificationDate(Carbon::create($this->updated_at));
    }

    public static function statusList(): array
    {
        return [
            self::STATUS_DRAFT  => 'Черновик',
            self::STATUS_ACTIVE => 'Опубликованный',
        ];
    }

    public static function statusLabel($status): string
    {
        return match ($status) {
            self::STATUS_DRAFT  => 'text-bg-secondary',
            self::STATUS_ACTIVE => 'text-bg-success',
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

    public function getImage($size, $number):null|string
    {
        if ($this->photos[$number-1]) {
            return 'storage/' . $this->photos[$number-1]->path . $size . '_' . $this->photos[$number-1]->name;
        }

        return null;
    }

    public function getImageAlt($number):null|string
    {
        if ($this->photos[$number-1]) {
            return $this->photos[$number-1]->alt_tag;
        }
        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive():bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'blog_category_post', 'post_id', 'category_id');
    }

    public function category():HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function photos():BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'blog_posts_photos','post_id', 'photo_id')->orderBy('sort');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

}
