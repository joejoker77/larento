<?php

namespace App\Entities\Site\Promotions;



use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Entities\Shop\Photo;
use App\Traits\WithMediaGallery;
use App\Events\PromotionOnDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $description_two
 * @property string $description_three
 * @property array $settings
 * @property array $meta
 * @property string $expiration_start
 * @property string $expiration_end
 *
 * @property Photo $photo
 *
 * @method Builder active()
 *
 */
class Promotion extends Model
{
    use HasFactory, WithMediaGallery;

    public const IMAGE_SIZE_SMALL = 150;

    public const IMAGE_SIZE_THUMB = 268;

    public const IMAGE_SIZE_MEDIUM = 640;

    public const IMAGE_SIZE_LARGE = 1440;

    public const IMAGE_SIZE_FULL = 1980;

    public const SETTINGS_POSITION_LEFT     = 'left';
    public const SETTINGS_POSITION_RIGHT    = 'right';
    public const SETTINGS_POSITION_TOP      = 'top';
    public const SETTINGS_POSITION_BOTTOM   = 'bottom';
    public const SETTINGS_WIDTH_TEXT_BLOCK  = 'width';
    public const SETTINGS_IS_MAIN_BANNER    = 'is_main_banner';

    public const IMAGE_PATH = 'files/promotions/';


    private const IMAGE_SIZES = [
        'small', 'thumb', 'medium', 'large', 'full'
    ];

    protected $table = 'shop_promotions';

    protected $fillable = [
        'name',
        'title',
        'description',
        'description_two',
        'description_three',
        'expiration_start',
        'expiration_end',
        'meta',
        'settings',
        'country',
        'photo_id'
    ];

    protected $casts = [
        'meta'                    => 'array',
        'settings'                => 'array',
        'expiration_start'        => 'date',
        'expiration_end'          => 'date',
        "settings.is_main_banner" => 'boolean'
    ];

    public static $searchable = ['title', 'description'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        Model::preventLazyLoading();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::deleting(function ($model) {
            PromotionOnDelete::dispatch($model);
        });
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

    public function getImage($size):null|string
    {
        $photo = $this->photo;

        return 'storage/' . $photo->path . $size . '_' . $photo->name;
    }

    public function statusBadge(): array
    {
        return $this->active()->get()->contains('id', $this->id) ? ['class' => 'text-bg-success', 'text' => 'Активно'] : ['class'=>'text-bg-danger', 'text'=>'Не активно'];
    }

    public function photo():BelongsTo
    {
        return $this->belongsTo(Photo::class, 'photo_id', 'id');
    }

    public function scopeActive(Builder $query): Builder
    {
        $where = $orWhere = $orWhereTwo = [];
        $where[]      = ['expiration_end', '>', Carbon::now()];
        $where[]      = ['expiration_start', '<', Carbon::now()];
        $orWhere[]    = ['expiration_end', '=', null];
        $orWhere[]    = ['expiration_start', '=', null];
        $orWhereTwo[] = ['expiration_start', '=', null];
        $orWhereTwo[] = ['expiration_end', '>', Carbon::now()];

        return $query->where($where)->orWhere($orWhere)->orWhere($orWhereTwo)->orWhere('expiration_end', null);
    }

}
