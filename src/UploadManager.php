<?php namespace zgldh\UploadManager;

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
}