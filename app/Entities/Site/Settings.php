<?php

namespace App\Entities\Site;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property string $name
 * @property string $address
 * @property string $work_time
 * @property string $slogan
 * @property array $emails
 * @property array $phones
 * @property integer $default_pagination
 * @property integer $quantity_offer
 * @property string $main_head
 * @property string $main_text
 *
 */
class Settings extends Model
{
    protected $table = 'shop_settings';

    protected $primaryKey = 'settings';

    protected $fillable = ['name','address','work_time','slogan','emails','phones','default_pagination','settings', 'main_text', 'main_head', 'quantity_offer'];

    protected $casts = ['emails' => 'array', 'phones' => 'array'];

    public $timestamps  = false;

    public $incrementing = false;

    public static function names():array
    {
        return [
            'name'               => 'Название Сайта',
            'address'            => 'Адрес магазина/офиса',
            'work_time'          => 'Время работы',
            'slogan'             => 'Слоган сайта',
            'emails'             => 'Почтовые ящики',
            'phones'             => 'Телефоны компании',
            'main_head'          => 'Заголовок на главной',
            'main_text'          => 'Текст на главной',
            'quantity_offer'     => 'Количество товаров на главной',
            'default_pagination' => 'Показывать на странице (товары/посты)'
        ];
    }
}
