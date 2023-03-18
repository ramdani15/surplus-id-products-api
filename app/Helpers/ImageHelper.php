<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageHelper
{
    /**
     * Default file storage
     * 'public' or 's3'
     */
    private static $disk = 'public';

    /**
     * Directory or Images / Files
     */
    public static $dir = '';

    /**
     * Upload description
     *
     * @param  image|string  $file
     * @return object
     **/
    public static function upload($file)
    {
        $file_name = (! empty($file->getClientOriginalName())) ? $file->getClientOriginalName() : basename($file);
        $img = Image::make($file);

        return self::putFile($img, $file_name);
    }

    /**
     * Put a file into S3
     *
     * @param  object  $img
     * @param  string  $file_name
     * @return array|bool
     */
    public static function putFile($img, string $file_name = null)
    {
        $rand = \Str::random(rand(10, 50)).time();
        $key = sha1($rand);
        $file_name = str_replace(' ', '-', $file_name);
        $full_path = date('Y/m/d').'/'.$key.'/'.$file_name;
        $img = self::encode($img, $file_name);
        $full_path = (! empty(self::$dir)) ? self::$dir.'/'.$full_path : $full_path;

        $isUploaded = Storage::disk(self::$disk)->put($full_path, $img->encoded, 'public');
        if (! $isUploaded) {
            return $isUploaded;
        }

        return [
            'size' => strlen($img->encoded),
            'width' => $img->width(),
            'height' => $img->height(),
            'mime_type' => $img->mime(),
            'file_name' => $file_name,
            'path' => $full_path,
            'url' => self::getUrl($full_path),
        ];
    }

    public static function getUrl($path)
    {
        return Storage::disk(self::$disk)->url($path);
    }

    /**
     * Set Disk
     *
     * @param self
     */
    public static function setDisk(string $disk)
    {
        self::$disk = $disk;
    }

    /**
     * Set Directory
     *
     * @param self
     */
    public static function setDir(string $dir)
    {
        self::$dir = $dir;
    }

    /**
     * Encode Image by extension
     *
     * @param  binary|string  $image
     * @param  string  $file_name
     * @return binary
     */
    private static function encode($image, $file_name)
    {
        $extension = explode('.', $file_name)[1];
        $image->encode($extension, 90);

        return $image;
    }
}
