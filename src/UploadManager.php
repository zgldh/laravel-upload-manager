<?php namespace zgldh\UploadManager;

use App\Upload;
use Illuminate\Support\Str;
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
     * @var UploadStrategyInterface
     */
    private $strategy = null;

    public function __construct()
    {
        $this->strategy = self::getStrategy();
    }

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

    /**
     * @param UploadedFile $file
     * @param null $preCallback
     * @return Upload|bool
     */
    public function upload(UploadedFile $file, $preCallback = null)
    {
        if (is_string($file)) {
            return $this->uploadByUrl($file, $preCallback);
        }

        $upload = new Upload();
        $upload->disk = \Config::get('upload.base_storage_disk');
        try {
            $new_name = $this->strategy->makeFileName($file);
            $path = $this->strategy->makeStorePath($new_name);

            if (is_callable($preCallback)) {
                $upload = $preCallback($upload);
            }

            if (!$upload) {
                return false;
            }

            $upload->path = $path;
            $upload->size = $file->getSize();

            $disk = \Storage::disk($upload->disk);
            if ($disk->put($path, file_get_contents($file->getPathname())) == false) {
                return false;
            }

        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }

        return $upload;
    }

    /**
     * @param $url
     * @param null $preCallback
     * @return Upload|bool
     */
    public function uploadByUrl($url, $preCallback = null)
    {
        $upload = new Upload();
        $upload->disk = \Config::get('upload.base_storage_disk');
        try {
            $new_name = $this->strategy->makeFileName($url);
            $path = $this->strategy->makeStorePath($new_name);

            if (is_callable($preCallback)) {
                $upload = $preCallback($upload);
            }

            if (!$upload) {
                return false;
            }

            $content = file_get_contents($url);

            $upload->path = $path;
            $upload->size = strlen($content);

            $disk = \Storage::disk($upload->disk);
            if ($disk->put($path, $content) == false) {
                return false;
            }

        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }

        return $upload;
    }

    public function getUploadUrl($disk, $path)
    {
        $url = '';
        $methodName = 'get' . ucfirst($disk) . 'Url';
        if (method_exists($this->strategy, $methodName)) {
            $url = $this->strategy->$methodName($path);
        }
        return $url;
    }
}