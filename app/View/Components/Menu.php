<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;
use App\Entities\Site\Navigations\NavItem;
use App\Entities\Site\Navigations\Menu as Navigation;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;

class Menu extends Component
{

    public array|Navigation $menu     = [];
    public array|Collection $navItems = [];
    public null|string $menu_id       = null;
    public string $header_tag         = 'h4';
    public string $header_class       = 'h4';
    public string $template           = 'components.menu';
    public string $menu_class         = 'navbar-nav';
    public string $drop_down          = 'true';
    public string $sub_classes        = '';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $handler,
        string $headerTag      = null,
        string $headerClass    = null,
        string $menuClass      = null,
        string $template       = null,
        string $menuId         = null,
        string $dropDown       = null,
        string $subMenuClasses = null
    ) {
        $findMenu = Navigation::where('handler', $handler)->first();
        if ($findMenu) {
            $this->navItems = NavItem::treeOf(function ($query) use ($findMenu) {
                $query->whereNull('parent_id')->where('menu_id', $findMenu->id);
            })->orderBy('sort')->get()->toTree();
            $this->menu = $findMenu;
        }
        if ($headerTag) {
            $this->header_tag = $headerTag;
        }
        if ($headerClass) {
            $this->header_class = $headerClass;
        }
        if ($menuClass) {
            $this->menu_class = $menuClass;
        }
        if ($template) {
            $this->template = $template;
        }
        if ($menuId) {
            $this->menu_id = $menuId;
        }
        if ($dropDown) {
            $this->drop_down = $dropDown;
        }
        if ($subMenuClasses) {
            $this->sub_classes = $subMenuClasses;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view($this->template, [
            'headerTag'      => $this->header_tag,
            'headerClass'    => $this->header_class,
            'menuClass'      => $this->menu_class,
            'dropDown'       => $this->drop_down,
            'subMenuClasses' => $this->sub_classes
        ]);
    }
}
