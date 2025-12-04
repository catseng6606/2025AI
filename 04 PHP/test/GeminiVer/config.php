<?php

// ============== 
// 環境變數載入
// ============== 
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// ============== 
// Session 安全設定
// ============== 
// 確保只在 HTTPS 上傳輸 Cookie
$cookie_secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
// 設定 Session Cookie 的屬性
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => $cookie_secure,
    'httponly' => true,
    'samesite' => 'Lax' // 或 'Strict'
]);

// 啟動 Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============== 
// 安全性標頭
// ============== 
// Content Security Policy (CSP)
// 根據需求調整 'self'、'unsafe-inline' 等來源
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data:");
// 防止點擊劫持
header("X-Frame-Options: DENY");
// 防止瀏覽器猜測 MIME 類型
header("X-Content-Type-Options: nosniff");
// 啟用 XSS 保護
header("X-XSS-Protection: 1; mode=block");
// HSTS (如果網站完全支援 HTTPS)
if ($cookie_secure) {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}


// ============== 
// CSRF Token 函數
// ============== 
/**
 * 產生並儲存一個 CSRF Token
 * @return string
 */
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * 驗證 CSRF Token
 * @param string $token
 * @return bool
 */
function verify_csrf_token(string $token): bool
{
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // Token 驗證後應立即銷毀
        unset($_SESSION['csrf_token']);
        return true;
    }
    return false;
}

// ============== 
// 輔助函數
// ============== 

/**
 * 安全地輸出 HTML
 * @param mixed $data
 * @return string
 */
function e(mixed $data): string
{
    return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
}

/**
 * 清洗 HTML，移除潛在的 XSS 攻擊程式碼
 * @param string $html
 * @return string
 */
function clean_html(string $html)
{
    // 移除 script 和 style 標籤
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

    // 移除 on* 事件屬性
    $html = preg_replace('/ on\w+="[^ vital]*"/i', '', $html);
    $html = preg_replace('/ on\w+=\'[^ vital]*\'/i', '', $html);

    // 移除 javascript: 偽協議
    $html = preg_replace('/href="javascript:[^ vital]*"/i', 'href="#"', $html);
    $html = preg_replace('/href=\'javascript:[^ vital]*\'/i', 'href="#"', $html);

    return $html;
}
