<?php

namespace App\Http\Controllers\Catalog;


use App\Events\NewComment;
use Illuminate\Http\Request;
use App\Entities\Shop\Product;
use App\Entities\Site\Settings;
use App\Entities\Shop\Category;
use App\Http\Router\ProductPath;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Site\CommentRequest;
use App\UseCases\ReadModels\SearchService;
use App\UseCases\ReadModels\CommentService;
use App\Http\Requests\Products\SearchRequest;
use Butschster\Head\Contracts\MetaTags\MetaInterface;

class CatalogController extends Controller
{

    private SearchService $search;

    private CommentService $commentService;

    protected MetaInterface $meta;

    private Settings $settings;

    public function __construct(SearchService $search, CommentService $commentService, MetaInterface $meta, Settings|null $settings)
    {
        $this->search         = $search;
        $this->meta           = $meta;
        $this->commentService = $commentService;

        if($settings) {
            $this->settings = $settings;
        }
    }

    public function index(SearchRequest $request, ProductPath $path): View|RedirectResponse
    {
        $settings = $this->settings ?? null;

        $category     = $path->category;
        $result       = $this->search->search($category, $request, $request->get('per-page', $this->settings['default_pagination']), $request->get('page', 1));
        $products     = $result->products;
        $query        = $category ? $category->children() : Category::whereIsRoot();
        $categories   = $query->active()->defaultOrder()->withDepth()->getModels();

        if (!$path->product) {
            $commentaries = new Collection();
            if ($category) {
                $category->products->map(function ($prod) use ($commentaries) {
                    $commentaries->push($prod->commentaries->map(function ($com) use ($prod) {
                        $com->product = $prod;
                        return $com;
                    }));
                });
                $commentaries = $commentaries->collapse();
            }

            if ($category && !empty($category->meta)) {
                $this->meta->setTitle($category->meta['title']);
                $this->meta->setDescription($category->meta['description']);
            }
            return view('shop.products.index', compact('category', 'categories', 'products', 'commentaries', 'settings'));
        } else {
            $product = $path->product;
            if (!empty($product->meta)) {
                $this->meta->setTitle($product->meta['title']);
                $this->meta->setDescription($product->meta['description']);
            }
            $settings['canonical'] = $product->canonical;
            return view('shop.products.show', compact('category', 'categories', 'product', 'products', 'settings'));
        }
    }

    public function filter(Request $request):View|RedirectResponse
    {
        $page     = $request->get('page') ?? 1;
        $perPage  = $request->get('per-page') ?? $this->settings['default_pagination'];
        $result   = $this->search->filter($request, $perPage, $page);
        $settings = $this->settings;

        $products       = $result->products;
        $restTags       = $result->tags;
        $restCategories = $result->categories;
        $restAttributes = $result->attributes;

        $this->meta->setTitle('Результат поиска по фильтру');
        $this->meta->setDescription('На данной странице отображаются товары к которым применен поисковый фильтр.');
        $this->meta->setRobots('noindex, nofollow');

        return view('shop.search.result', compact('request', 'products', 'restAttributes', 'restTags', 'restCategories','settings'));
    }

    public function search(Request $request): View
    {

        return view('shop.search.search-result');
    }

    public function addComment(CommentRequest $request, Product $product):RedirectResponse
    {
        try {
            $result = $this->commentService->sendComment($request, $product);
            NewComment::dispatch($result['comment']);
            return back()->with('success', 'Ваш коментарий добавлен. Он будет опубликован на сайте после прохождения модерации')->cookie($result['cookie']);
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
