<?php

namespace App\Http\Controllers\Admin\Shop;


use App\Traits\QueryParams;
use Illuminate\View\View;
use App\Entities\Shop\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Entities\Site\Promotions\Promotion;
use App\UseCases\Admin\Shop\PromotionService;
use App\Http\Requests\Admin\Shop\PromotionRequest;

class PromotionController extends Controller
{

    use QueryParams;

    private PromotionService $service;


    public function __construct(PromotionService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request):View
    {
        $query = Promotion::with('photo');
        $this->queryParams($request, $query);
        $promotions = $query->paginate(20);

        return \view('admin.shop.promotion.index', compact('promotions'));
    }


    public function create():View
    {
        return view('admin.shop.promotion.create');
    }

    public function store(PromotionRequest $request):RedirectResponse
    {
        $this->service->create($request);

        return \redirect(route('admin.shop.promotion.index'))->with('success', 'Акция/Баннер успешно создана');
    }

    public function edit(?Promotion $promotion)
    {
        return view('admin.shop.promotion.edit', compact('promotion'));
    }

    public function show () : RedirectResponse
    {
        return \redirect(route('admin.shop.promotion.index'))->with('warning', 'Страница просмотра недоступна для акций');
    }

    public function update(PromotionRequest $request, Promotion $promotion):RedirectResponse
    {
        try {
            $this->service->update($request, $promotion);
            return redirect()->route('admin.shop.promotion.index')->with('success', 'Акция/Баннер успешно обновлен');
        } catch (\Exception|\DomainException $e) {
            return redirect()->route('admin.shop.promotion.edit', $promotion)->with('error', $e->getMessage());
        }
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();
        return redirect()->route('admin.shop.promotion.index')->with('success', 'Акция/Баннер успешно удален');
    }

    public function removeBatch (Request $request):RedirectResponse
    {
        try {
            $selected = $request->get('selected');

            if (empty($selected)) {
                return back()->with('error', 'Не выбрано ни одного элемента');
            }
            Promotion::find($selected)->each(function($promotion) {
                $promotion->delete();
            });
            return back()->with('success', 'Все выбранные акции успешно удалены');
        } catch (\PDOException $exception) {
            return back()->with('error', 'При удалении произошла ошибка: '.$exception->getMessage());
        }
    }


    /**
     * @throws \Throwable
     */
    public function photoRemove (Promotion $promotion, Photo $photo) {
        try {
            $promotion->removePhoto($photo->id, Promotion::getImageParams()['sizes']);
            return redirect()->back()->with('success', 'Фото успешно удалено.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
