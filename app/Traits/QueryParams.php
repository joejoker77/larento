<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;

trait QueryParams {

    public function queryParams(Request $request, $query)
    {
        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }
        if (!empty($value = $request->get('title'))) {
            $query->where('title', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('sku'))) {
            $query->where('sku', $value);
        }
        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('user_name'))) {
            $query->where('user_name', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('user_phone'))) {
            $query->where('user_phone', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('product_name'))) {
            $query->where('product_name', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('product'))) {
            $query->whereHas('product', function ($q) use($value) {
                $q->where('name', 'like', '%' . $value . '%');
            });
        }
        if (!empty($value = $request->get('category'))) {
            $catIds = Category::whereDescendantOrSelf($value)->pluck('id');
            $query->whereIn('category_id', $catIds);
        }
        if (!empty($value = $request->get('expiration_start'))) {
            $query->where('expiration_start', '>=', Carbon::parse($value))->orWhere('expiration_start', null);
        }
        if (!empty($value = $request->get('expiration_end'))) {
            $query->where('expiration_end', '<=', Carbon::parse($value))->orWhere('expiration_end', null);
        }
        if (!empty($value = $request->get('created_start'))) {
            $query->where('created_at', '>=', Carbon::parse($value));
        }
        if (!empty($value = $request->get('created_end'))) {
            $query->where('created_at', '<=', Carbon::parse($value));
        }
        if (!empty($value = $request->get('status'))) {
            $query->where('status', '=', $value);
        }
        if (!empty($value = $request->get('current_status'))) {
            $query->where('current_status', '=', $value);
        }
        if (!empty($value = $request->get('vote'))) {
            $query->where('vote', '=', $value);
        }
        if ($request->get('hit') == 'true') {
            $query->where('hit', true);
        }
        if($request->get('new') == 'true') {
            $query->where('new', true);
        }
        if($request->get('display_home') == 'true') {
            $query->where('display_home', true);
        }
        if(!empty($value = $request->get('sort'))) {
            if ($value[0] == '-') {
                $value = str_replace('-', '', $value);
                if ($value == 'product') {
                    $query->orderBy(
                        Product::select('name')
                            ->whereColumn('id', 'product_id')
                            ->orderBy('name', 'desc'),
                        'DESC'
                    );
                } else {
                    $query->orderBy($value, 'DESC');
                }
            } else {
                if ($value == 'product') {
                    $query->orderBy(
                        Product::select('name')
                            ->whereColumn('id', 'product_id')
                            ->orderBy('name', 'asc'),
                        'asc'
                    );
                } else {
                    $query->orderBy($value, 'asc');
                }
            }
        } else {
            $query->orderBy('id');
        }
        return $query;
    }
}
