# laravel-upload-manager
Upload, validate, storage, manage by API for Laravel 5.1

## Requirement

1. Laravel 5.1

## Install

1. composer require zgldh/laravel-upload-manager
2. ```app.php```  ```'providers' => [ 'zgldh\UploadManager\UploadManagerServiceProvider']```
3. php artisan vendor:publish --provider="zgldh/UploadManager/UploadManagerServiceProvider"
4. php artisan migrate
5. Done

## Usage

1. Upload and store a file.
    
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
 
2. Fetch and store a file from a URL
    
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
 
3. Update a upload object
    
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
 
4. Update a upload object from a URL
    
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