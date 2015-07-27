<?php namespace zgldh\UploadManager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Created by PhpStorm.
 * User: ZhangWB
 * Date: 2015/7/23
 * Time: 16:50
 */
class UploadManager
{
    /**
     * @return UploadManager
     */
    public static function getInstance()
    {
        return \App::make('upload-manager');
    }

    /**
     * @return UploadStrategyInterface
     */
    public static function getStrategy()
    {
        return \App::make('zgldh\UploadManager\UploadStrategyInterface');
    }

    public function upload(UploadedFile $file)
    {
        if (is_string($file)) {
            return $this->uploadByUrl($file);
        }

        $info = false;
        try {
            $new_name = date('Y-m-d-') . md5(md5_file($file->getRealPath()) . time()) . '.' . $file->getClientOriginalExtension();
            $path = 'i/' . $new_name;
            $disk = \Storage::disk(self::DEFAULT_STORAGE_DISK);
            $disk->put($path, file_get_contents($file->getPathname()));
            $info = $this->getImageInfo($disk, $path);
            $info['path'] = $path;
            $info['size'] = $file->getSize();
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return $info;
    }

    public function uploadByUrl($url)
    {

    }
}