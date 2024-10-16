<?php

namespace App\Http\Controllers\Blog;


use App\Http\Router\PostPath;
use App\Entities\Site\Settings;
use App\Http\Controllers\Controller;
use App\Entities\Site\Promotions\Promotion;
use Butschster\Head\Contracts\MetaTags\MetaInterface;

class BlogController extends Controller
{
    protected MetaInterface $meta;
    private Settings $settings;

    public function __construct(MetaInterface $meta, Settings|null $settings)
    {
        $this->meta = $meta;
        if ($settings) {
            $this->settings = $settings;
        }
    }

    public function index(PostPath $path)
    {
        $settings = $this->settings ?? null;

        if ($path->post) {

            $post = $path->post;

            $this->meta->setTitle($post->meta['title']);
            $this->meta->setDescription($post->meta['description']);

            return view('blog.'.$post->category->template_name.'.show', compact('post', 'settings'));

        } else if ($path->category) {

            $category = $path->category;
            $posts    = $category->posts()->paginate(5, ['*'], 'posts');

            $this->meta->setTitle($category->meta['title']);
            $this->meta->setDescription($category->meta['description']);

            return view('blog.'.$category->template_name.'.index', compact('category', 'settings', 'posts'));
        }
        return back(404);
    }
}
