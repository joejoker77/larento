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
    private int $currentCategoryId;

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

        // $this->formFilterData = $this->getFormData($request, $currentCategoryId);
    }

    public function render():View
    {
        return view('components.filter', ['filter' => $this->filter, 'currentCategoryId' => $this->currentCategoryId]);
    }

    private function getFormData(Request $request, $catId):array
    {
        $result = [];
        $inputs = $request->input();

        if (empty($this->filter)) {
            return $result;
        }

        foreach ($this->filter->groups as $filterGroup) {
            $result[$filterGroup->name]['displayHeader'] = $filterGroup->display_header;
            $result[$filterGroup->name]['id'] = $filterGroup->id;

            if ($filterGroup->categories) {
                foreach ($filterGroup->categories as $categoryId) {
                    /** @var Category $category */
                    $category = Category::findOrFail($categoryId);
                    $result[$filterGroup->name]['categories'][] = $category;
                }
            }
            if ($filterGroup->tags) {
                foreach ($filterGroup->tags as $tagId) {
                    /** @var Tag $tag */
                    $tag = Tag::findOrFail($tagId);

                    $result[$filterGroup->name]['tags'][] = $tag;

                }
            }
            if ($filterGroup->attributes) {
                $arrayAttributes = [];
                $dbValues        = Value::all(['attribute_id', 'value']);
                $attributes      = Attribute::with('categories')->get();

                foreach ($filterGroup->attributes as $attributeId) {
                    /** @var $attribute Attribute */
                    $attribute = $attributes->map(function ($item) use($attributeId, $catId) {
                        return $item->id == $attributeId && $item->allCategories()->contains($catId) ? $item : null;
                    })->filter()->first();

                    if (($attribute && $attribute->type === Attribute::TYPE_FLOAT) || ($attribute && $attribute->type === Attribute::TYPE_INTEGER)) {
                        $values    = $attribute->variants;
                        $newValues = $newEqualValues = [];
                        foreach ($values as $value) {
                            if (preg_match('/[А-я]/', $value) === 0) {
                                $newValues[] = (float)$value;
                            } else {
                                $newEqualValues[] = (string)$value;
                            }
                        }
                        sort($newValues);
                        $strNewValues = array_map(function ($val) use ($attribute) {
                            $res =  $attribute->unit ? '"'.$val.' '.$attribute->unit.'"' : '"'.$val.'"';
                            return trim($res);
                        },$newValues);
                        $attribute->variants = $strNewValues;

                        if (!empty($newEqualValues)) {
                            $attribute->newEquals = $newEqualValues;
                        }
                    }

                    if (!empty($inputs['attributes'])) {
                        foreach ($inputs['attributes'] as $attrId => $reqValues) {
                            if ($attrId == $attribute->id) {
                                $attribute->selected = $reqValues;
                            }
                        }
                    }
                    $r = array_map(function ($variant) use($dbValues, $attribute) {
                        return $dbValues->map(function ($value) use($attribute, $variant) {
                            if ($attribute->id == $value->attribute_id && str_contains($value->value, $variant)) {
                                return $variant;
                            }
                            return null;
                        });
                    }, $attribute->variants);

                    $attribute->variants = $attribute->type == Attribute::TYPE_BOOLEAN ? ['0', '1'] :
                        array_filter(collect($r)->collapse()->unique()->toArray());
                    $arrayAttributes[] = $attribute;

                }
                if (!empty($arrayAttributes)) {
                    $collectionAttrs = new Collection($arrayAttributes);
                    $result[$filterGroup->name]['attributes'] = $collectionAttrs->sortBy('sort');
                }
            }
        }
        return $result;
    }
}
