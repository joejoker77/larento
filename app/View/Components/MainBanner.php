<?php

namespace App\View\Components;


use Illuminate\View\View;
use Illuminate\View\Component;
use App\Entities\Site\Promotions\Promotion;

class MainBanner extends Component
{
    public function render():View
    {
        $promotions = Promotion::active()->where(['settings->is_main_banner' => '1'])->with('photo')->orderBy('settings->sort', 'asc')->get();

        return view("components.main-banner", compact("promotions"));
    }
}
