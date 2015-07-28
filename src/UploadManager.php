<?php namespace zgldh\UploadManager;

use App\Upload;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Created by PhpStorm.
 * User: zgldh
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

    public function getUploadUrl($disk, $path)
    {
        $url = '';
        $methodName = 'get' . Str::camel($disk) . 'Url';
        if (method_exists($this->strategy, $methodName)) {
            $url = $this->strategy->$methodName($path);
        }
        return $url;
    }


    /**
     * @param $upload
     * @param $uploadedFilePath
     * @param $file
     * @param $preCallback
     * @return bool
     */
    private function coreUpload($upload, $uploadedFilePath, $file, $preCallback)
    {
        try {
            $newName = $this->strategy->makeFileName($file);
            $path = $this->strategy->makeStorePath($newName);

            if (is_callable($preCallback)) {
                $upload = $preCallback($upload);
            }

            if (!$upload) {
                return false;
            }

            $content = file_get_contents($uploadedFilePath);

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

    /**
     * 保存上传文件，生成上传对象
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

        $uploadedFilePath = $file->getPathname();
        $upload = $this->coreUpload($upload, $uploadedFilePath, $file, $preCallback);

        return $upload;
    }

    /**
     * 从URL获取文件并保存，生成上传对象
     * @param $url
     * @param null $preCallback
     * @return Upload|bool
     */
    public function uploadByUrl($url, $preCallback = null)
    {
        $upload = new Upload();
        $upload->disk = \Config::get('upload.base_storage_disk');

        $uploadedFilePath = $url;
        $upload = $this->coreUpload($upload, $uploadedFilePath, $url, $preCallback);

        return $upload;
    }

    /**
     * 用已上传文件更新一个上传对象
     * @param $upload
     * @param UploadedFile $file
     * @param null $preCallback
     * @return bool
     */
    public function update(&$upload, UploadedFile $file, $preCallback = null)
    {
        if (is_string($file)) {
            return $this->updateByUrl($upload, $file, $preCallback);
        }
        $oldDisk = $upload->disk;
        $oldPath = $upload->path;

        $uploadedFilePath = $file->getPathname();
        $result = $this->coreUpload($upload, $uploadedFilePath, $file, $preCallback);
        if ($result) {
            $this->removeOldFile($oldDisk, $oldPath);
            $upload = $result;
        } else {
            $upload->disk = $oldDisk;
            $upload->path = $oldPath;
            return false;
        }
        return true;
    }

    /**
     * 用URL更新一个上传对象
     * @param $upload
     * @param $url
     * @param null $preCallback
     * @return bool
     */
    public function updateByUrl(&$upload, $url, $preCallback = null)
    {
        $oldDisk = $upload->disk;
        $oldPath = $upload->path;

        $uploadedFilePath = $url;
        $result = $this->coreUpload($upload, $uploadedFilePath, $url, $preCallback);
        if ($result) {
            $this->removeOldFile($oldDisk, $oldPath);
            $upload = $result;
        } else {
            $upload->disk = $oldDisk;
            $upload->path = $oldPath;
            return false;
        }

        return true;
    }

    private function removeOldFile($disk, $path)
    {
        if ($disk && $path) {
            $disk = \Storage::disk($disk);
            if ($disk) {
                $disk->delete($path);
            }
        }
    }
}