<?php

namespace App\Http\Requests\Admin\Shop;


use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            "name"                    => "required|string|max:255",
            "title"                   => "required|string|max:255",
            "description"             => "nullable|string",
            "description_two"         => "nullable|string",
            "description_three"       => "nullable|string",
            "photo"                   => "nullable|image|mimes:jpg,jpeg,png",
            "meta"                    => "nullable|array|min:2",
            "meta.*"                  => "nullable|string|max:255",
            "settings"                => "nullable|array",
            "settings.left"           => "nullable|integer",
            "settings.right"          => "nullable|integer",
            "settings.top"            => "nullable|integer",
            "settings.bottom"         => "nullable|integer",
            "settings.width"          => "nullable|integer",
            "settings.sort"           => "nullable|integer",
            "settings.hide_text"      => "boolean",
            "settings.is_main_banner" => "boolean",
            "expiration_start"        => "nullable|dateformat:d.m.Y",
            "expiration_end"          => "nullable|dateformat:d.m.Y",
        ];
    }
}
