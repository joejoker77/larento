<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'text' => 'nullable|string',
            'attrs.*.equals' => 'nullable|string',
            'attrs.*.from' => 'nullable|numeric',
            'attrs.*.to' => 'nullable|numeric',
            'per-page'   => 'nullable|numeric',
            'page'      => 'nullable|numeric'
        ];
    }
}
