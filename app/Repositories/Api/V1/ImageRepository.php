<?php

namespace App\Repositories\Api\V1;

use App\Helpers\ImageHelper;
use App\Models\Image;

class ImageRepository
{
    /**
     * Do upload file
     *
     * @param File object $file
     * @param  bool  $enable
     */
    public function upload($file, $enable = true)
    {
        $disk = config('filesystems.default');
        ImageHelper::setDisk($disk);
        ImageHelper::setDir('images');

        $uploaded = ImageHelper::upload($file);

        if (! $uploaded) {
            return $uploaded;
        }

        $newData = $uploaded;
        $newData['name'] = $newData['file_name'];
        $newData['enable'] = $enable;
        $newData['file'] = $newData['url'];
        $origin = Image::create($newData);

        return $origin;
    }
}
