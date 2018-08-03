<?php
/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/7/23
 * Time: 16:52
 */

return [

    /**
     * 请在filesystems.php 的 disks 数组里挑一个
     **/
    'base_storage_disk' => 'local',

    /**
     * 对应的数据模型类
     **/
    'upload_model'      => App\Upload::class,

    /**
     * 上传策略类
     **/
    'upload_strategy'   => zgldh\UploadManager\UploadStrategy::class,

    /**
     * validator group 用于 withValidator()函数 common是默认的。
     **/
    'validator_groups'  => [
        'common' => [
            /**
             * 请参考 http://laravel.com/docs/5.1/validation
             **/
            'min' => 0,  //kilobytes    
            'max' => 4096,  //kilobytes
        ],
        'image'  => [
            'max'   => 8192,  //kilobytes
            'mimes' => 'jpeg,bmp,png,gif'
        ]
    ],

    /**
     * 已上传，但未使用的文件的过期时长。单位是秒
     * 设置为 -1 则不会删除未使用的文件。
     * 如果一个 Upload 对象没有 uploadable 关联对象， 则该 Upload 对象未使用。
     */
    'unused_lifetime'   => -1
];