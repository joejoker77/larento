<?php

namespace App\Traits;


use Throwable;
use App\Entities\Shop\Photo;
use Illuminate\Support\Facades\Storage;

trait WithMediaGallery {
    /**
     * @throws Throwable
     */
    public function removePhoto($id, $sizes):void
    {
        $photo = Photo::find($id);

        foreach ($sizes as $nameSize => $size) {
            if (Storage::disk('public')->exists($photo->path.$nameSize.'_'.$photo->name)) {
                Storage::disk('public')->delete($photo->path.$nameSize.'_'.$photo->name);
            }
        }
        $files = Storage::disk('public')->files($photo->path);
        if (empty($files)) {
            Storage::disk('public')->deleteDirectory($photo->path);
        }
        $photo->delete();
    }

    /**
     * @throws Throwable
     */
    public function movePhotoUp($id):void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {

                if ($prev = $photos[$i - 1] ?? null) {
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Изображение не найдено.');
    }

    /**
     * @throws Throwable
     */
    public function movePhotoDown($id):void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($prev = $photos[$i + 1] ?? null) {
                    $photos[$i + 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Изображение не найдено.');
    }

    /**
     * @throws Throwable
     */
    private function updatePhotos($photos):void
    {
        /**
         * @var Photo $photo
         */
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
            $photo->update();
        }
    }
}
