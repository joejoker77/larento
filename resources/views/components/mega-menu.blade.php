@props(['settings'])
<nav class="{{ $menu_class }}" id="{{ $menu_id }}">
    <div class="container-fluid">
        <button class="navbar-toggler open" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarLarento" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbarLarento">
            <div class="d-flex flex-row justify-content-between d-lg-none mobile-header-top">
                <div>Меню</div>
                <button class="navbar-toggler close" data-bs-toggle="collapse" data-bs-target="#mainNavbarLarento" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
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
            @isset($settings)
            <div class="d-flex flex-column d-lg-none text-center navbar_company-info justify-content-end h-100">
                <div class="navbar_company-link-phones">
                    @foreach(format_phones($settings['phones']) as $phone)
                        <a href="{{$phone['link']}}" class="navbar_company-link-phone">{{ $phone['phone'] }}</a>
                    @endforeach
                </div>
                <div class="navbar_company-clockwork">
                    {{ $settings['work_time'] ?? env('APP_WORK_TIME') }}
                </div>
                <div class="navbar_company-email">
                    <a href="mailto:info@larento.ru">{{ $settings['emails'][0] ?? env('MAIL_FROM_ADDRESS') }}</a>
                </div>
                <div class="navbar_company-address mt-auto">
                    {{ $settings['address'] ?? env('APP_ADDRESS') }}
                </div>
            </div>
            @endisset
        </div>
    </div>
</nav>


