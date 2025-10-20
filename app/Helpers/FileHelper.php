<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

if (!function_exists('getFileUrl')) {
  /**
   * Get full file URL from storage or return default.
   *
   * @param string|null $path   Path in storage/app/public
   * @param string $default     Path in public/ for default image
   * @return string
   */
  function getFileUrl(?string $path, string $default = 'assets/img/default-image.jpg'): string
  {
    if (empty($path)) {
      return asset($default);
    }

    if (Storage::disk('public')->exists($path)) {
      return Storage::url($path);
    }

    return asset($default);
  }


  if (!function_exists('uploadAndProcessImage')) {

    /**
     * Upload an image, convert to WebP, resize, and optionally delete old file.
     *
     * @param UploadedFile $image
     * @param string $directory
     * @param string|null $oldFile
     * @param int $width
     * @param int $height
     * @return string 
     */
    function uploadAndProcessImage(UploadedFile $image, string $directory, ?string $oldFile = null, int $width = 600, int $height = 600): string
    {
      $filename = Str::uuid() . '.webp';
      $fullPath = storage_path("app/public/{$directory}/{$filename}");

      if (!file_exists(dirname($fullPath))) {
        mkdir(dirname($fullPath), 0755, true);
      }

      Image::read($image)
        ->resize($width, $height, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })
        ->toWebp(90)
        ->save($fullPath);


      if ($oldFile && Storage::disk('public')->exists($oldFile)) {
        Storage::disk('public')->delete($oldFile);
      }

      return "{$directory}/{$filename}";
    }
  }
}
