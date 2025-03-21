<?php

namespace App\Http\Controllers\Admin\Blog;


use Doctrine\DBAL\Cache\CacheException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Entities\Blog\Post;
use App\Entities\Blog\Category;
use App\Http\Controllers\Controller;
use App\UseCases\Admin\Blog\PostService;
use App\Http\Requests\Admin\Blog\PostRequest;
use Throwable;
use App\Entities\Shop\Photo;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private PostService $service;


    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function index():View
    {
        $posts      = Post::with(['category', 'photos'])->paginate(20);
        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.blog.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.blog.posts.create', compact('categories'));
    }

    public function store(PostRequest $request):RedirectResponse
    {
        try {
            $post = $this->service->create($request);
            return redirect()->route('admin.blog.posts.show', compact('post'))
                ->with('success', 'Статья успешно создана');
        } catch (\Throwable $e) {
            return back()->with('error', 'Во время выполнения запроса, произошла следующая ошибка: '. $e->getMessage());
        }
    }

    public function edit(Post $post):View
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.blog.posts.edit', compact('post','categories'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        try {
            $this->service->update($request, $post);
            return redirect()->route('admin.blog.posts.show', $post)->with('success', 'Статья успешно обновлена');
        } catch (\Exception|\DomainException $e) {
            echo $e->getMessage();
            return redirect()->route('admin.blog.posts.edit', $post)->with('error', $e->getMessage());
        }
    }

    public function show(Post $post):View
    {
        return view('admin.blog.posts.show', compact('post'));
    }

    public function destroy(Post $post):RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.blog.posts.index');
    }

    /**
     * @throws Throwable
     */
    public function photoUp (Post $post, Photo $photo): RedirectResponse
    {
        try {
            $post->movePhotoUp($photo->id);
            return redirect()->back()->with('success', 'Фото успешно перемещено.');
        } catch (\DomainException $e) {
            return redirect()->route('admin.blog.posts.show', compact('post'))
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function photoDown (Post $post, Photo $photo): RedirectResponse
    {
        try {
            $post->movePhotoDown($photo->id);
            return redirect()->back()->with('success', 'Фото успешно перемещено.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function photoRemove (Post $post, Photo $photo): RedirectResponse
    {
        try {
            $post->removePhoto($photo->id, Post::getImageParams()['sizes']);
            return redirect()->back()->with('success', 'Фото успешно удалено.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function setStatus(Post $post): RedirectResponse
    {
        if ($post->isActive()) {
            $post->status = Post::STATUS_DRAFT;
        } else {
            $post->status = Post::STATUS_ACTIVE;
        }
        $post->save();
        return back()->with('success', 'Статус статьи успешно изменен')->withInput();
    }
}
