<?php

namespace App\View\Components;


use Illuminate\View\View;
use App\Entities\Blog\Post;
use Illuminate\View\Component;
use App\Entities\Blog\Category;

class PortfolioModule extends Component
{
    public function render(): View
    {
        $posts = Post::whereIn('category_id',
            Category::where('slug', 'portfolio')
                ->pluck('id')->toArray())
            ->with(['photos', 'category'])
            ->active()
            ->get();

        return view('components.portfolio-module', compact('posts'));
    }
}
