<?php

namespace App\Http\Requests\Admin\Shop;


use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules =  [
            "name"                 => "required|string|max:255",
            "description"          => "nullable|string",
            "sku"                  => "nullable|unique:App\Entities\Shop\Product,sku|string|max:255",
            "category_id"          => "nullable|exists:App\Entities\Shop\Category,id",
            "photo"                => "nullable|array",
            "photo.*"              => "nullable|image|mimes:jpg,jpeg,png",
            "meta"                 => "nullable|array|min:2",
            "meta.*"               => "nullable|string|max:255",
            "product_categories"   => "nullable|array",
            "product_categories.*" => "nullable|exists:App\Entities\Shop\Category,id",
            "product_tags"         => "nullable|array",
            "product_tags.*"       => "nullable|exists:App\Entities\Shop\Tag,id",
            "product_attributes"   => "nullable|array",
        ];

        if ($this->isMethod('patch')) {
            $id = $this->product->id;
            $rules['sku'] = "nullable|unique:\App\Entities\Shop\Product,sku,".$id."|string|max:255";
        }

        return $rules;
    }
}
