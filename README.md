# laravel-upload-manager
通过API对文件进行“上传、验证、储存、管理”操作。
Upload, validate, storage, manage by API for Laravel 5.1

## 需求 Requirement

1. Laravel 5.1

## 安装 Install

1. composer require zgldh/laravel-upload-manager
2. ```app.php```  ```'providers' => [ 'zgldh\UploadManager\UploadManagerServiceProvider']```
3. php artisan vendor:publish --provider="zgldh\UploadManager\UploadManagerServiceProvider"
4. php artisan migrate
5. Done

## 用法 Usage

1. 上传一个文件 Upload and store a file.
    
    ```php
     
        use zgldh\UploadManager\UploadManager;
        
        class UploadController extend Controller
        {
            public function postUpload(Request $request)
            {
                $file = $request->file('avatar');
                $uploadManager = UploadManager::getInstance();
                $upload = $uploadManager->upload($file);
                $upload->save();
                return $upload;
            }
        }
    ```
 
2. 从一个URL获取并保存文件 Fetch and store a file from a URL
    
    ```php
     
        use zgldh\UploadManager\UploadManager;
        
        class UploadController extend Controller
        {
            public function postUpload(Request $request)
            {
                $fileUrl = $request->input('url');
                $uploadManager = UploadManager::getInstance();
                $upload = $uploadManager->upload($fileUrl);
                $upload->save();
                return $upload;
            }
        }
    ```
 
3. 更新一个上传对象 Update a upload object
    
    ```php
     
        use App\Upload;
        use zgldh\UploadManager\UploadManager;
        
        class UploadController extend Controller
        {
            public function postUpload(Request $request)
            {
                $uploadId = $request->input('id');
                $file = $request->file('avatar');
                
                $uploadManager = UploadManager::getInstance();
                $upload = Upload::find($uploadId);
                if($uploadManager->upload($upload, $file))
                {
                    $upload->save();
                    return $upload;
                }
                return ['result'=>false];
            }
        }
    ```
 
4. 用从一个URL获取到的文件来更新一个上传对象 Update a upload object from a URL
    
    ```php
     
        use App\Upload;
        use zgldh\UploadManager\UploadManager;
        
        class UploadController extend Controller
        {
            public function postUpload(Request $request)
            {
                $uploadId = $request->input('id');
                $fileUrl = $request->input('url');
                
                $uploadManager = UploadManager::getInstance();
                $upload = Upload::find($uploadId);
                if($uploadManager->upload($upload, $fileUrl))
                {
                    $upload->save();
                    return $upload;
                }
                return ['result'=>false];
            }
        }
    ```
    
## 配置 Configuration

1. ``` config/upload.php ```
2. ``` App\Upload ```
3. ``` UploadStrategy.php ```

待续
    