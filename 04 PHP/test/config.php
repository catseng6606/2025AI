<?php
// config.php

// 讀取 .env 檔案
function loadEnv($path)
{
    if (!file_exists($path)) {
        return [];
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }
        list($name, $value) = $parts;
        $env[trim($name)] = trim($value);
    }
    return $env;
}

$env = loadEnv(__DIR__ . '/.env');
$uploadDir = isset($env['UPLOAD_DIR']) ? rtrim($env['UPLOAD_DIR'], '/') . '/' : 'uploads/';

// Session 設定
// 檢查是否為 HTTPS
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isSecure, // 僅在 HTTPS 下開啟
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

// CSRF Token 生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>