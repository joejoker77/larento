@php $intlFormatter = new IntlDateFormatter('ru_RU', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT); $intlFormatter->setPattern('d MMMM Y'); @endphp
<section id="ourWork">
    <div class="our-work_head">
        <h2 class="decor-head-two">наши работы</h2>
        <p class="text-center">Мы надеемся, что наше портфолио вдохновит вас на новые идеи и поможет вам лучше понять, что предлагает компания Larento. Мебель, которую мы создаем, становится символом качества и надежности.</p>
    </div>
    <div class="our-work_container d-flex flex-row justify-content-between gap-4 px-4 mb-5">
        <div class="our-work_left d-flex flex-column gap-4">
            @php /** @var $post App\Entities\Blog\Post */ @endphp
            @foreach($posts->slice(0, 2) as $key => $post)
                <div class="our-work_top">
                    <div class="our-work_image">
                        <img src="{{ $post->getImage('medium', 1) }}" alt="{{ $post->photos[0]->alt_tag }}">
                    </div>
                    <div class="our-work_info">
                        <div class="catalog-start_item-cat-title">Опубликовано: {{ $intlFormatter->format($post->created_at) }}</div>
                        <div class="catalog-start_item-title">{{$post->title}}</div>
                    </div>
                    <a href="{{ route('blog.index', post_path($post->category, $post)) }}" class="stretched-link"></a>
                </div>
            @endforeach
        </div>
        @foreach($posts->slice(2, 1) as $key => $post)
            <div class="our-work_center">
                <div class="our-work_image">
                    <img src="{{ $post->getImage('medium', 1) }}" alt="{{ $post->photos[0]->alt_tag }}">
                </div>
                <div class="our-work_info">
                    <div class="catalog-start_item-cat-title">Опубликовано: {{ $intlFormatter->format($post->created_at) }}</div>
                    <div class="catalog-start_item-title">{{$post->title}}</div>
                </div>
                <a href="{{ route('blog.index', post_path($post->category, $post)) }}" class="stretched-link"></a>
            </div>
        @endforeach
        <div class="our-work_left d-flex flex-column gap-4">
            @foreach($posts->slice(3, 2) as $key => $post)
                <div class="our-work_top">
                    <div class="our-work_image">
                        <img src="{{ $post->getImage('medium', 1) }}" alt="{{ $post->photos[0]->alt_tag }}">
                    </div>
                    <div class="our-work_info">
                        <div class="catalog-start_item-cat-title">Опубликовано: {{ $intlFormatter->format($post->created_at) }}</div>
                        <div class="catalog-start_item-title">элен мадера</div>
                    </div>
                    <a href="{{ route('blog.index', post_path($post->category, $post)) }}" class="stretched-link"></a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="our-work_button text-center">
        <a href="{{ route('blog.index', post_path($post->category, null)) }}" class="btn btn-brown">все работы</a>
    </div>
</section>

