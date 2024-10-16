<?php

namespace App\Entities\Shop;


use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $user_name
 * @property string $user_phone
 * @property string $subject
 * @property string $product_name
 * @property string $note
 * @property array $statuses
 * @property integer $current_status
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class Order extends Model
{

    protected $table = 'shop_orders';

    protected $fillable = ['user_name', 'user_phone', 'subject', 'product_name', 'note', 'statuses', 'current_status'];

    public $casts = [
        'statuses' => 'array'
    ];

    public static function statusesList():array
    {
        return [
            Status::NEW                   => 'Новый',
            Status::CANCELLED             => 'Отменен',
            Status::PROGRESS              => 'В работе',
            Status::PAID                  => 'Оплачен',
            Status::COMPLETED             => 'Завершен',
            Status::CANCELLED_BY_CUSTOMER => 'Отменен пользователем'
        ];
    }

    public static function getStatus($status):string
    {
        return self::statusesList()[$status];
    }

    public static function statusLabel($status): string
    {
        return match ($status) {
            Status::NEW                   => 'text-bg-primary',
            Status::PAID                  => 'text-bg-warning',
            Status::PROGRESS              => 'text-bg-info',
            Status::COMPLETED             => 'text-bg-success',
            Status::CANCELLED_BY_CUSTOMER => 'text-bg-danger',
            Status::CANCELLED             => 'text-bg-danger',
            default => 'text-bg-light',
        };
    }

    public function addStatus($value):void
    {
        $statuses             = $this->statuses;
        $statuses[]           = new Status($value, time());
        $this->statuses       = $statuses;
        $this->current_status = $value;
        $this->save();
    }

    public function setNote($note):void
    {
        $this->note = $note;
        $this->save();
    }
}
