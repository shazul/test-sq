<?php

namespace Pimeo\ImageHelper;

use Illuminate\Support\Facades\App;
use Intervention\Image\ImageManager;

class ImageHelper
{
    /**
     * @var ImageManager
     */
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(['driver' => 'gd']);
    }

    /**
     * Create the display image and the thumbnail image.
     * @param string $filename
     */
    public function createResizeImages($filename)
    {
        $date = $this->getDateFromImage($filename);
        $name = $this->getImageName($filename);

        $full_filename = getenv('FILES_PATH_LOCAL') . $filename;
        if (App::environment('local')) {
            $full_filename = '/www/sites/pim.soprema.local/public/img/generic.png';
        }

        $image_thumbnail = $this->manager->make($full_filename);
        $image_display = $this->manager->make($full_filename);

        $image_thumbnail->resize(250, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image_display->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image_thumbnail_path = getenv('FILES_PATH_LOCAL') . $date . '/thumbnail_' . $name;

        if (App::environment('local')) {
            $image_thumbnail_path = '/www/sites/pim.soprema.local/public/img/generic.png';
        }

        $image_display->save($full_filename);
        $image_thumbnail->save($image_thumbnail_path);
    }

    public function getThumbnailName($filename)
    {
        $date = $this->getDateFromImage($filename);
        $name = $this->getImageName($filename);
        return $date . '/thumbnail_' . $name;
    }

    /**
     * Check if the image exist in thumbnail format
     *
     * @param $filename
     * @return bool
     */
    public function thumbnailExist($filename)
    {
        $date = $this->getDateFromImage($filename);
        $name = $this->getImageName($filename);
        return file_exists(getenv('FILES_PATH_LOCAL') . $date . '/thumbnail_' . $name);
    }

    private function getDateFromImage($filename)
    {
        $exploded_path = explode('/', $filename);
        return $exploded_path[0];
    }

    private function getImageName($filename)
    {
        $exploded_path = explode('/', $filename);
        unset($exploded_path[0]);

        return implode('', $exploded_path);
    }
}
