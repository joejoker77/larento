<?php

namespace App\Http\Controllers\Admin\Shop;


use Throwable;
use App\Entities\Shop\Tag;
use App\Traits\QueryParams;
use App\Entities\Shop\Photo;
use Illuminate\Http\Request;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\UseCases\Admin\Shop\ProductService;
use App\Http\Requests\Admin\Shop\ProductRequest;


class ProductController extends Controller
{
    use QueryParams;

    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request):View
    {
        $query = Product::with(['category', 'photos']);
        $this->queryParams($request, $query);

        $products   = $query->paginate(20);
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.shop.products.index', compact('products', 'categories'));
    }

    public function create():View
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        $tags       = Tag::orderBy('name')->get();
        return view('admin.shop.products.create', compact('categories', 'tags'));
    }

    public function store(ProductRequest $request):RedirectResponse|Product
    {
        try {
            $product = $this->service->create($request);
            return redirect()->route('admin.shop.products.show', compact('product'))
                ->with('success', 'Продукт успешно создан');
        } catch (Throwable $e) {
            return back()->with('error', 'Во время выполнения запроса, произошла следующая ошибка: '. $e->getMessage());
        }
    }

    public function edit(Product $product):View
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        $tags       = Tag::orderBy('name')->get();
        return view('admin.shop.products.edit', compact('product', 'tags', 'categories'));
    }

    /**
     * @throws Throwable
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->service->update($request, $product);
            return redirect()->route('admin.shop.products.show', $product)->with('success', 'Товар успешно обновлен');
        } catch (\Exception|\DomainException $e) {
            echo $e->getMessage();
            return redirect()->route('admin.shop.products.edit', $product)->with('error', $e->getMessage());
        }
    }

    public function show(Product $product):View
    {
        return view('admin.shop.products.show', compact('product'));
    }

    public function destroy(Product $product):RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.shop.products.index');
    }

    public function getAttributesForm(Request $request): View
    {
        $category   = Category::ancestorsAndSelf($request["id"])->first();
        $attributes = $category->allAttributes();

        return view('admin.shop.products.partials.attributes', compact('attributes'));
    }

    /**
     * @throws Throwable
     */
    public function photoUp (Product $product, Photo $photo): RedirectResponse
    {
        try {
            $product->movePhotoUp($photo->id);
            return redirect()->back()->with('success', 'Фото успешно перемещено.');
        } catch (\DomainException $e) {
            return redirect()->route('admin.shop.categories.show', compact('product'))
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function photoDown (Product $product, Photo $photo): RedirectResponse
    {
        try {
            $product->movePhotoDown($photo->id);
            return redirect()->back()->with('success', 'Фото успешно перемещено.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function photoRemove (Product $product, Photo $photo): RedirectResponse
    {
        try {
            $product->removePhoto($photo->id, Product::getImageParams()['sizes']);
            return redirect()->back()->with('success', 'Фото успешно удалено.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function setActive(Product $product):RedirectResponse
    {
        $product->published();
        return back()->with('success', 'Товар успешно опубликован');
    }

    public function setUnActive(Product $product):RedirectResponse
    {
        $product->unPublished();
        return back()->with('success', 'Товар успешно снят с публикации.');
    }

    public function setStatus(Request $request): RedirectResponse
    {
        try {
            $answer = $this->service->setStatus($request);
            return back()->with('success', $answer)->withInput();
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function searchRelation(Request $request):JsonResponse
    {
        $answer = $this->service->searchVariant($request->get('query'));
        return response()->json($answer);
    }


    public function deleteRelation(Request $request): JsonResponse
    {
        try {
            /** @var Product $product */
            $product = Product::find($request->get('current_product'));
            $product->related()->detach($request->get('variant_id'));
            Session::flash('success', 'Связанный продукт успешно удален');
            return response()->json('success');
        } catch (\Exception $exception) {
            return response()->json(['error'=> $exception->getMessage()]);
        }
    }

    public function addRelation(Request $request): RedirectResponse
    {
        try {
            /** @var Product $product */
            $product = Product::find($request->get('current_product'));
            $product->related()->attach($request->get('variant_id'));
            return redirect()->route('admin.shop.products.edit', compact('product'))->with('success', 'Связанный продукт успешно добавлен к текущему');
        } catch (\Exception $exception) {
            return redirect()->route('admin.shop.products.edit', compact('product'))->with('error', $exception->getMessage());
        }
    }
}
