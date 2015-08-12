<?php
/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/7/23
 * Time: 16:52
 */

return [
    'base_storage_disk' => 'local', // 请在filesystems.php 的 disks 数组里挑一个
    'upload_strategy'   => zgldh\UploadManager\UploadStrategy::class,
    'validator_groups'  => [
        // validator group 用于 withValidator()函数 common是默认的。
        'common' => [
            //validators
            'min' => 0,  //kilobytes    请参考 http://laravel.com/docs/5.1/validation
            'max' => 4096,  //kilobytes
        ],
        'image'  => [
            'max'   => 8192,  //kilobytes
            'mimes' => 'jpeg,bmp,png,gif'
        ]
    ]
];