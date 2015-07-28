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
];