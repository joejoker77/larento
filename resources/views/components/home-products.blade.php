@php /** @var $products App\Entities\Shop\Product[] */ @endphp

@if($products->isNotEmpty())
    <section id="catalog">
        <div class="catalog-start d-flex flex-wrap justify-content-between">
            @php /** @var $product App\Entities\Shop\Product */ @endphp
            @foreach($products as $product)
                <div class="catalog-start_item position-relative">
                    <div class="label @if($product->isHit())hit @elseif($product->isNew())new @endif"></div>
                    <div class="catalog-start_item-img position-relative">
                        <img src="{{ $product->getMainImage('thumb') }}" alt="пример товара">
                        <a href="{{ route('catalog.index', product_path($product->category, $product)) }}" class="stretched-link"></a>
                    </div>
                    <div class="catalog-start_item-info d-flex flex-column">
                        <div class="catalog-start_item-cat-title"><a href="{{ route('catalog.index', product_path($product->category, null)) }}">{{ $product->category->title }}</a></div>
                        <div class="catalog-start_item-title"><a href="{{ route('catalog.index', product_path($product->category, $product)) }}">{{ $product->name }}</a></div>
                        <div class="catalog-start_item-price">Цену уточняйте у менеджера</div>
                    </div>
                    <button type="button" class="catalog-start_item-button" name="js-modal" data-product-name="{{ $product->name }}">заказать</button>
                </div>
            @endforeach
        </div>
        <div class="catalog-link text-center">
            <a href="{{ route('catalog.index',product_path(null, null)) }}" class="btn btn-brown" data-product-id="{{ $product->id }}">каталог продукции</a>
        </div>
    </section>
@endif
