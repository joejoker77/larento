@props(['items', 'default_classes', 'li_classes', 'dropDown', 'subMenuClasses'])
@foreach($items as $item)
    @if(isset($item->item_path))
        @php
            $classes = $default_classes;
            if (request()->is(trim($item->item_path, '/').'*')) {
                $classes .= ' active';
            }

            if ($item->children->isNotEmpty() && $dropDown == 'true') {
                $classes .= ' dropdown-toggle';
            }
        @endphp
        <li class="{{$liClasses}}">
            <a class="{{ $classes }}"
               href="{{ $item->item_path }}"
               @if($item->children->isNotEmpty() && $dropDown == 'true') data-bs-toggle="dropdown" aria-expanded="false" @endif
               @if($item->image) data-image="{{ $item->image }}" @endif
               @if($item->entity_type == 'external' and !str_contains($item->item_path, '#')) target="_blank" @endif
            >
                {{ $item->link_text ?? $item->title }}
            </a>
            @if($item->children->isNotEmpty())
                @php
                    $liSubClass = !empty($subMenuClasses) ?  $classes . ' sub-link' : $classes;
                    $linkSubClass = !empty($subMenuClasses) ? $liClasses . ' sub-item' : $liClasses;
                @endphp
                <ul class="{{ $subMenuClasses }} @if($dropDown == 'true')dropdown-menu @endif">
                    <x-nav-item :items="$item->children" default_classes="{{$liSubClass}}" li_classes="{{$linkSubClass}}" />
                </ul>
            @endif
        </li>
    @endif
@endforeach
