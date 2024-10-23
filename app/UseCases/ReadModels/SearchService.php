<?php

namespace App\UseCases\ReadModels;


use App\Traits\QueryParams;
use App\Entities\Blog\Post;
use App\Entities\Shop\Value;
use Illuminate\Http\Request;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;
use Illuminate\Database\Query\Expression;
use App\Http\Requests\Products\SearchResult;
use App\Http\Requests\Products\FilterResult;
use App\Http\Requests\Site\AjaxSearchResult;
use App\Http\Requests\Products\SearchRequest;
use App\Entities\Blog\Category as BlogCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SearchService
{
    use QueryParams;

    private array $models = [
        Post::class         => ['name' => 'Статья', 'type'          => 'post'],
        Category::class     => ['name' => 'Категория', 'type'       => 'category'],
        BlogCategory::class => ['name' => 'Категория блога', 'type' => 'blog_category'],
        Product::class      => ['name' => 'Продукт', 'type'         => 'product']
    ];

    public function search(?Category $category, SearchRequest $request, int $perPage, int $page): SearchResult
    {
        if ($category) {
            $query = Product::active()
                ->where('category_id', $category->id)
                ->orWhereIn('id', $category->siblingProducts()
                    ->pluck('id')
                    ->toArray())
                ->with(['category', 'values', 'photos', 'commentaries'])
                ->offset(($page-1) * $perPage)
                ->limit($perPage);

            $totalCount = Product::active()->where('category_id', $category->id)
                ->orWhereIn('id', $category->siblingProducts()
                    ->pluck('id')
                    ->toArray())
                ->count();

            $items      = $this->queryParams($request, $query)->get();
            $pagination = new LengthAwarePaginator($items, $totalCount, $perPage, $page);

            return new SearchResult(
                $pagination,
                [$category->id => $totalCount]
            );
        }
        $pagination = new LengthAwarePaginator([], 0, $perPage, $page);
        return new SearchResult(
            $pagination,
            []
        );
    }

    public function filter(Request $request, int $perPage, int $page)
    {
        $values = array_filter((array)$request->input('attributes'), function ($value) {
            return !empty($value['equals']) || !empty($value['min']) || !empty($value['max']);
        });

        $category = Category::find($request->get('currentCategoryId'));
        $catIDs   = $category ? Category::ancestorsAndSelf($category->id)->pluck('id') : null;
        $query    = Value::select('product_id', DB::raw('count(`product_id`) as cnt'));

        array_map(function ($value, $id) use($query) {
            if(count($value['equals']) > 1) {
                foreach ($value['equals'] as $equal) {
                    $equal == '1' ?
                        $query->orWhere('attribute_id', $id)->where('value', $equal) :
                        $query->orWhere('attribute_id', $id)->whereLike('value', '%'.$equal.'%');
                }
            } else if($value['equals'][0] == '1') {
                $query->orWhere('attribute_id', $id)->where('value', $value['equals'][0]);
            } else {
                $query->orWhere('attribute_id', $id)->whereLike('value', '%'.$value['equals'][0].'%');
            }
        }, $values, array_keys($values));

        $query->groupBy('product_id')->having('cnt', '>', count($values) - 1);

        $totalCount = $query->distinct('product_id')->count();

        if ($totalCount > 0 && $catIDs) {
            $items = Product::active()->with(['photos', 'values', 'category', 'tags', 'categories', 'category.parent'])
                ->whereIn('id', array_unique($query->pluck('product_id')->toArray()))
                ->whereIn('category_id', $catIDs)
                ->orderBy(new Expression('FIELD(id,' . implode(',', array_unique($query->pluck('product_id')->toArray())) . ')'))
                ->offset(($page-1) * $perPage)
                ->limit($perPage)->get();
        } elseif ($totalCount > 0 && !$catIDs) {
            $items = Product::active()->with(['photos', 'values', 'category', 'tags', 'categories', 'category.parent'])
                ->whereIn('id', array_unique($query->pluck('product_id')->toArray()))
                ->orderBy(new Expression('FIELD(id,' . implode(',', array_unique($query->pluck('product_id')->toArray())) . ')'))
                ->offset(($page-1) * $perPage)
                ->limit($perPage)->get();
        } else {
            $items = [];
        }

        $pagination = $totalCount > 0 ?
            new LengthAwarePaginator($items, $totalCount, $perPage, $page) :
            new LengthAwarePaginator([], 0, $perPage, $page);

        return new FilterResult($pagination, [], [], []);
    }

    public function searchFromString(Request $request, $perPage): AjaxSearchResult
    {
        $blogCategories = $categories = $posts = $products = [];

        if (!$query = $request->get('query')) {
            abort(400);
        }
        foreach ($this->models as $model => $modelArray) {
            $q = $model::query()->with('photos');

            if ($modelArray['type'] == 'category') {
                $q->with(['products', 'products.photos']);
            } else if ($modelArray['type'] == 'product') {
                $q->with(['category', 'values'])->paginate($perPage);
            }

            $fields = $model::$searchable;

            foreach ($fields as $field) {
                $q->orWhere($field, 'LIKE', '%'.$query.'%');
            }

            $results = $q->get();

            foreach ($results as $result) {
                if($result instanceof BlogCategory) {
                    $blogCategories[] = $result;
                } else if ($result instanceof Category) {
                    $categories[] = $result;
                } else if ($result instanceof Post) {
                    $posts [] = $result;
                } else if ($result instanceof Product) {
                    $products[] = $result;
                }
            }
        }
        return new AjaxSearchResult($products, $posts, $blogCategories, $categories);
    }

    public function getTotalProducts(string $query)
    {
        return Product::orWhere('name', 'LIKE', '%'.$query.'%')->orWhere('sku', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->count();
    }
}
