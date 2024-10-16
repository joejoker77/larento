<?php

namespace App\Listeners;

use App\Events\PromotionOnDelete;

class ClearPromotionFiles
{
    /**
     * Handle the event.
     */
    public function handle(PromotionOnDelete $event): void
    {
        $photoPaths = [];
        $imgParams  = $event->promotion::getImageParams();
        $photo      = $event->promotion->photo()->first();

        $event->promotion->photo()->delete();

        if ($photo) {
            foreach ($imgParams['sizes'] as $sizeName => $size) {
                $photoPaths[] = $imgParams['path'].$sizeName . '_' . $photo->name;
            }
        }
        \Storage::disk('public')->delete($photoPaths);
    }
}
