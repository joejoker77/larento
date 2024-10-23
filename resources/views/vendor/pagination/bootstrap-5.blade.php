@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex flex-column justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
            <div>
                @php

                    $notify = match ($paginator->getPageName()) {
                        'comments' => 'комментариев',
                        'posts'    => 'работ',
                        'orders'   => 'заявок',
                        default => 'товаров',
                    };

                @endphp
                <p class="text-total">
                    {!! __('pagination.results', ['items' => $notify]) !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                </p>
            </div>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between pagination-wrapper">
            <div>
                <ul class="pagination px-0 px-md-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">@lang('pagination.previous')</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">@lang('pagination.previous')</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">@lang('pagination.next')</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">@lang('pagination.next')</span>
                        </li>
                    @endif
                </ul>
            </div>
            <div>
                @php

                    $notify = match ($paginator->getPageName()) {
                        'comments' => 'комментариев',
                        'posts'    => 'работ',
                        'orders'   => 'заявок',
                        default => 'товаров',
                    };

                @endphp
                <p class="text-total">
                    {!! __('pagination.results', ['items' => $notify]) !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                </p>
            </div>
        </div>
    </nav>
@endif
