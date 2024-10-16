<?php

namespace App\Listeners;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Alexusmai\LaravelFileManager\Events\FilesUploaded;

class ConvertImages
{


    /**
     * Handle the event.
     *
     * @param FilesUploaded $event
     * @return void
     */
    public function handle(FilesUploaded $event):void
    {
        foreach ($event->files() as $file) {

            $prefixPath = Storage::disk('public')->path('/');
            $originalName = str_replace($file["extension"], '', $file["name"]);
            $originalPath = str_replace($file["name"], '', $file["path"]);

            $path = $originalPath === '/' ? $prefixPath : $prefixPath.$originalPath;

            $img = Image::read($path.$file['name']);
            $img->toWebp(70);

            Storage::disk('public')->put($originalPath.$originalName.'webp', $img->core()->native());
            Storage::disk('public')->delete($originalPath.$file['name']);
        }
    }
}
