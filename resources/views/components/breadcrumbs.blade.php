@if (!$breadcrumbs->isEmpty() && $breadcrumbs->count() > 1)
    <div class="breadcrumbs">
        <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '-';">
            <ol class="breadcrumb">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->url && !$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{!! $breadcrumb->title !!}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{!! $breadcrumb->title !!}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
@endif
