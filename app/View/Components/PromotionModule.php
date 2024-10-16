<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;
use App\Entities\Site\Promotions\Promotion;

class PromotionModule extends Component
{
    public function render(): View
    {
        $promotions = Promotion::where('settings->is_main_banner', null)->with('photo')->active()->get();

        return view('components.promotion-module', compact('promotions'));
    }
}
