<?php namespace zgldh\UploadManager;

/**
 * Created by PhpStorm.
 * User: ZhangWB
 * Date: 2015/7/23
 * Time: 16:50
 */


use Illuminate\Support\ServiceProvider;

class UploadManagerServiceProvide extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
        $this->app->singleton('zgldh\UploadManager\UploadStrategyInterface', \Config::get('upload.upload_strategy'));
        $this->app->singleton('upload-manager', 'zgldh\UploadManager\UploadManager');

    }

    public function boot()
    {

    }
}