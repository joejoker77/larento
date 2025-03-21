@if($menu && $menu->show_title)
    <{{ $headerTag }} class="{{ $headerClass }}">{{ $menu->title }}</{{ $headerTag }}>
@endif
<ul class="{{ $menuClass }}" @if($menu_id) id="{{ $menu_id }}" @endif>
    @if($menu)
        <x-nav-item :items="$navItems" default_classes="nav-link" li_classes="nav-item" dropDown="{{$dropDown}}" subMenuClasses="{{$subMenuClasses}}" />
    @endif
</ul>
