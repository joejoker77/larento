<?php

namespace App\UseCases\Admin\Shop;


use Throwable;
use Illuminate\Support\Str;
use App\Entities\Shop\File;
use App\Entities\Shop\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Shop\CategoryRequest;

/**
 *
 */
class CategoryService
{
    /**
     * @throws Throwable
     */
    public function create(CategoryRequest $request): Category
    {
        DB::beginTransaction();
        try {
            $category = Category::make([
                "name"              => $request['name'],
                "slug"              => $request['slug'],
                "title"             => $request['title'],
                "parent_id"         => $request['parent_id'],
                "short_description" => $request['short_description'],
                "description"       => $request['description'],
                "published"         => $request['published'],
                "meta"              => $request['meta']
            ]);

            $category->save();

            if ($request->get('attributes')) {
                $category->attributes()->attach($request->get('attributes'));
            }

            $this->checkImages($request, $category);
            $this->checkFiles($request, $category);

            DB::commit();
            return $category;
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \DomainException('Сохранение сущности Category завершилось с ошибкой. Подробности: ' . $e->getMessage());
        }
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return void
     */
    public function update(CategoryRequest $request, Category $category): void
    {
        $category->update($request->only([
            'name',
            'slug',
            'title',
            'parent_id',
            'short_description',
            'description',
            'published',
            'meta']), [
            "name"              => $request['name'],
            "slug"              => $request['slug'],
            "title"             => $request['title'],
            "parent_id"         => $request['parent_id'] ?? null,
            "short_description" => $request['short_description'],
            "description"       => $request['description'],
            "published"         => $request['published'],
            "meta"              => $request['meta']
        ]);

        if (!$request['parent_id']) {
            $category->saveAsRoot();
        }

        $category->attributes()->detach();
        if ($request->get('attributes')) {
            $category->attributes()->attach($request->get('attributes'));
        }

        $this->checkImages($request, $category);
        $this->checkFiles($request, $category);
    }

    /**
     * @throws Throwable
     */
    public function togglePublished(Category $category): bool
    {
        if($category->isPublished()) {
            $category->unPublished();
        } else {
            $category->publised();
        }
        return $category->isPublished();
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return void
     */
    private function checkImages(CategoryRequest $request, Category $category): void
    {
        if ($images = $request->file('photo')) {
            foreach ($images as $i => $image) {
                if ($category->photos()->where('name', '=', str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName()) . '.webp')->first()) {
                    continue;
                }
                $photo = $category->photos()->create([
                    "name" => str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName()) . '.webp',
                    "sort" => $i,
                    "path" => Category::getImageParams()['path'] . Str::slug($category->name) . '/images/'
                ]);
                save_image_to_disk($image, $photo, Category::getImageParams()['sizes']);
            }
        }
    }

    private function checkFiles(CategoryRequest $request, Category $category): void
    {
        if ($files = $request->file('files')) {
            foreach ($files as $i => $file) {
                $info = $file->getFileInfo();
                $type = $info->getExtension() == 'mp4' || $info->getExtension() == 'mov' || $info->getExtension() == 'm4v' ? File::TYPE_VIDEO : File::TYPE_DOCUMENT;
                /** @var File $f */
                $f = $category->files()->create([
                    "name" => $info->getFilename(),
                    "sort" => $i,
                    "path" => Category::IMAGE_PATH . Str::slug($category->name).'/'.$type.'/',
                    'type' => $type,
                ]);
                Storage::put($f->path.$f->name, file_get_contents($file));
            }
        }
    }
}
