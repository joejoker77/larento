<?php


use App\Entities\Blog\Post;
use App\Entities\Shop\Status;
use App\Http\Router\PostPath;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;
use App\Http\Router\ProductPath;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Entities\Blog\Category as BlogCategory;


if (!function_exists('to_boolean')) {

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    function to_boolean($booleable): bool
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

if (!function_exists('save_image_to_disk')) {

    function save_image_to_disk($file, $object, $sizes, $setWaterMark = null): void
    {
        $img = Image::read($file->getRealPath());
        foreach ($sizes as $nameSize => $size) {
//            if ($setWaterMark && $nameSize !== 'small' && $nameSize !== 'thumb' && $nameSize !== 'medium') {
//                $img->place(
//                    Storage::disk('public')->path('images/home/watermark3.png'),
//                    'center',
//                    0,
//                    0,
//                    5
//                );
//            }
            $cloneImg = clone $img;
            $cloneImg->scale((int)$size)->toWebp(60);
            Storage::disk('public')->put($object->path . $nameSize . '_' . $object->name, $cloneImg->core()->native());
            unset($cloneImg);
        }
    }
}

if (!function_exists('ru_plural')) {

    function ru_plural ($number, $titles = ['комментарий','комментария','комментариев']): string
    {
        $cases = array (2, 0, 1, 1, 1, 2);

        return $number.' '.$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}

if (!function_exists('product_path')) {
    /**
     * @throws Illuminate\Contracts\Container\BindingResolutionException
     */
    function product_path(?Category $category, ?Product $product)
    {
        return app()->make(ProductPath::class)->withCategory($category)->withProduct($product);
    }
}

if (!function_exists('post_path')) {
    /**
     * @throws Illuminate\Contracts\Container\BindingResolutionException
     */
    function post_path(?BlogCategory $category, ?Post $post)
    {
        return app()->make(PostPath::class)->withCategory($category)->withPost($post);
    }
}

if (!function_exists('country_to_flag')) {
    function country_to_flag($country):string
    {
        $countryMap = [
            ['name_ru'=>'Германия','name_en'=>'Germany','code'=>'DE'],
            ['name_ru'=>'Италия','name_en'=>'Italy','code'=>'IT'],
            ['name_ru'=>'Россия','name_en'=>'Russia','code'=>'RU'],
            ['name_ru'=>'Нидерланды','name_en'=>'Netherlands','code'=>'NL'],
            ['name_ru'=>'Турция','name_en'=>'Turkey', 'code'=>'TR'],
            ['name_ru'=>'Бельгия','name_en'=>'Belgium','code'=>'BE'],
            ['name_ru'=>'Франция','name_en'=>'France','code'=>'FR']
        ];

        if (count_chars($country) != 2) {
            foreach ($countryMap as $countryArray) {
                if (strtolower($countryArray['name_ru']) == strtolower($country) || strtolower($countryArray['name_en']) == strtolower($country)) {
                    $country = $countryArray['code'];
                    break;
                }
            }
        }

        return (string) preg_replace_callback('/./', static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),$country);
    }
}

if (!function_exists('get_order_label')) {
    function get_order_label($status): array
    {
        return match ($status) {
            Status::NEW                   => ['class' => 'badge bg-secondary', 'label' => 'Новый'],
            Status::PAID                  => ['class' => 'badge bg-warning', 'label' => 'Оплачен'],
            Status::SENT                  => ['class' => 'badge bg-dark', 'label' => 'Отправлен'],
            Status::COMPLETED             => ['class' => 'badge bg-success', 'label' => 'Завершен'],
            Status::CANCELLED             => ['class' => 'badge bg-danger', 'label' => 'Отменен'],
            Status::CANCELLED_BY_CUSTOMER => ['class' => 'badge bg-dark-danger', 'label' => 'Отменен покупателем']
        };
    }
}

if (!function_exists('get_max_from_string')) {
    function get_max_from_string(array $values, string $postfix) {
        $numberValues = array_map(function ($value) use ($postfix) {
            return trim(str_replace($postfix, '', str_replace('"', '',$value)));
        }, $values);
        return max($numberValues);
    }
}

if (!function_exists('get_min_from_string')) {
    function get_min_from_string(array $values, string $postfix) {
        $numberValues = array_map(function ($value) use ($postfix) {
            return trim(str_replace($postfix, '', str_replace('"','',$value)));
        }, $values);
        return min($numberValues);
    }
}

if (!function_exists('format_phones')) {
    function format_phones(array $phones) {
        $result = [];
        foreach ($phones as $phone) {
            $phoneNumber  = str_replace('+7', '8', $phone);
            $formatNumber = substr($phoneNumber, 0, 1).' '.'('.substr($phoneNumber, 1, 3).') '.substr($phoneNumber, 4, 3).' '.substr($phoneNumber,7,2).' '.substr($phoneNumber, 9, 2);
            $phoneLink = 'tel:'.$phone;
            $phoneWhatsAppLink = 'https://wa.me/'.$phone;
            $result[] = ['phone' => $formatNumber, 'link' => $phoneLink, 'whatsAppLink' => $phoneWhatsAppLink];
        }
        return $result;
    }
}
