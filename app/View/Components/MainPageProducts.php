<?php

namespace App\View\Components;


use Illuminate\View\View;
use App\Entities\Shop\Product;
use Illuminate\View\Component;
use App\Entities\Site\Settings;


class MainPageProducts extends Component
{

    private Settings|null $settings;


    public function __construct(?Settings $settings)
    {
        $this->settings = $settings;
    }

    public function render():View
    {
        $limit    = $this->settings ? $this->settings['quantity_offer'] : 8;
        $products = Product::active()->where(['display_home' => '1'])->with('photos', 'category')->limit($limit)->get();

        return view("components.home-products", compact("products"));
    }
}
