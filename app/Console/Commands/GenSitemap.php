<?php

namespace App\Console\Commands;


use App\Entities\Blog\Post;
use Spatie\Sitemap\Sitemap;
use App\Entities\Shop\Product;
use App\Entities\Shop\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Entities\Blog\Category as BlogCategory;

class GenSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle():void
    {
        $sitemap = Sitemap::create()->add(Category::all())->add(Product::all())->add(BlogCategory::all())->add(Post::all());
        $sitemap->writeToFile(public_path('/sitemap.xml'));
    }
}
