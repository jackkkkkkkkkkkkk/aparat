<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('to_valid_mobile_number')) {
    function to_valid_mobile_number($number)
    {
        return '0' . substr($number, -10, 10);
    }
}
if (!function_exists('create_random_activation_code')) {
    function create_random_activation_code()
    {
        return random_int(111111, 999999);
    }
}
if (!function_exists('uniqueId')) {
    function uniqueId($id)
    {
        $hashId = new \Hashids\Hashids('aparat', 8);
        $hashId = $hashId->encode($id);
        return $hashId;
    }
}
if (!function_exists('clear_storage')) {
    function clear_storage($storageName)
    {
        Storage::disk($storageName)->delete(Storage::disk($storageName)->allFiles());
        foreach (Storage::disk($storageName)->allDirectories() as $dir) {
            Storage::disk($storageName)->deleteDirectory($dir);
        }
    }
}
if (!function_exists('client_ip')) {
    function client_ip()
    {
        return $_SERVER['REMOTE_ADDR'] . '-' . md5($_SERVER['HTTP_USER_AGENT']);
    }
}
if (!function_exists('comment_data')) {
    function comment_data($comments, $parentId = null)
    {
        $result = [];
        foreach ($comments as $index => $comment) {
            if ($comment->parent_id === $parentId) {
                $data = $comment->toArray();
                $data['children'] = comment_data($comments, $comment->id);
                $result[] = $data;
            }
        }
        return $result;
    }
}



