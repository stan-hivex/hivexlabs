<?php
$_SERVER['SCRIPT_NAME'] = '/users/software_detail.php';
function asset_path($path) {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#', $path)) {
        return $path;
    }

    $isRootRelative = strpos($path, '/') === 0;
    $encodedSegments = array_map('rawurlencode', array_filter(explode('/', ltrim($path, '/')), 'strlen'));
    $path = implode('/', $encodedSegments);
    if ($isRootRelative) {
        return '/' . $path;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $appRoot = dirname(dirname($scriptName));
    if ($appRoot === '/' || $appRoot === '.' || $appRoot === '\\') {
        $appRoot = '';
    }

    return ($appRoot === '' ? '' : $appRoot) . '/' . $path;
}

echo asset_path('uploads/1776777493_poshivex.png') . "\n";
