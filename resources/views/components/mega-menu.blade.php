<nav class="{{ $menu_class }}" id="{{ $menu_id }}">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach($navItems as $key => $navItem)
                    <li class="nav-item @if($navItem->children->isNotEmpty())dropdown @endif @if(request()->is(trim($navItem->item_path, '/').'*')) active @endif">
                        <a class="nav-link @if($navItem->children->isNotEmpty())dropdown-toggle @endif" href="{{$navItem->item_path}}" @if($navItem->children->isNotEmpty()) data-bs-toggle="dropdown" aria-expanded="false" @endif @if($navItem->entity_type == 'external') target="_blank" @endif>
                            {{$navItem->link_text ?? $navItem->title}}
                        </a>
                        @if($navItem->children->isNotEmpty())
                            <ul class="dropdown-menu">
                                <x-nav-item :items="$navItem->children" default_classes="dropdown-item" li_classes="nav-item" />
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>


