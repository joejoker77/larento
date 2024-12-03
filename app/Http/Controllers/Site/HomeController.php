<?php

namespace App\Http\Controllers\Site;


use App\Entities\Site\Settings;
use App\UseCases\ReadModels\HomeService;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Butschster\Head\Contracts\MetaTags\MetaInterface;


class HomeController extends Controller
{

    private MetaInterface $meta;
    private Settings $settings;
    private HomeService $service;

    public function __construct(MetaInterface $meta, HomeService $service, Settings|null $settings)
    {
        $this->service = $service;
        $this->meta = $meta;
        if ($settings) {
            $this->settings = $settings;
        }
    }

    public function index(): View
    {
        try {
            $this->meta->setTitle('Интернет магазин мебели - "Larento"');
            $this->meta->setDescription('Кухни дешево на заказ,производитель кухонь Larento - заказать кухню недорого хорошего качества на заказ в Москве');
            $settings = $this->settings;
            $reviews = $this->service->getReviews();

            return \view('home', compact('settings', 'reviews'));
        } catch (\Throwable $e) {
            return \view('home')->with('error', $e->getMessage());
        }
    }
}
