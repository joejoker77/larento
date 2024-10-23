<?php

namespace App\View\Components;

use App\Entities\Shop\Value;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use App\Entities\Shop\Tag;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use App\Entities\Shop\Category;
use App\Entities\Shop\Attribute;
use App\Entities\Shop\Filter as FilterEntity;

class Filter extends Component
{

    private FilterEntity $filter;
    private int|null $currentCategoryId;

    public function __construct(string $position, Request $request, $currentCategoryId = null)
    {
        $this->currentCategoryId = $currentCategoryId ?? $request->get('currentCategoryId');
        $this->filter            = FilterEntity::where('position', $position)->with('groups')->first() ?? [];
        $contains                = array_intersect(Category::ancestorsAndSelf($this->currentCategoryId)->pluck('id')->toArray(), $this->filter->categories->pluck('id')->toArray());

        if (empty($contains)) return null;

        foreach ($this->filter->groups as $group) {
            $attributes = Attribute::whereIn('id',$group->attributes)->with(['categories'])->get()->map(function ($attr) use($contains) {
                return !empty(array_intersect($contains, $attr->categories->pluck('id')->toArray())) ? $attr : null;
            })->filter();

            $group->sortAttributes = $attributes->sortBy('sort');
        }

        if (empty($request->input())) {
            return null;
        }
    }

    public function render():View
    {
        return view('components.filter', ['filter' => $this->filter, 'currentCategoryId' => $this->currentCategoryId]);
    }
}
