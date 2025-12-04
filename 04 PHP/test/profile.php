<?php
// profile.php

// 設定 cookie 安全屬性 (必須在 session_start 前)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
// 產生 CSP nonce
$csp_nonce = base64_encode(random_bytes(16));
// 設定 CSP header (允許 Bootstrap CDN 與字型，inline style/script 需 nonce，connect-src jsdelivr)
header("Content-Security-Policy: default-src 'self'; connect-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net 'nonce-$csp_nonce'; style-src 'self' https://cdn.jsdelivr.net 'nonce-$csp_nonce'; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net data:;");

$env = parse_ini_file(__DIR__.'/.env');
$upload_dir = $env['UPLOAD_DIR'] ?? 'uploads/';
$data = $_SESSION['profile'] ?? null;
if (!$data) {
    header('Location: edit.php');
    exit;
}
// 色系
$pink = '#f8bbd0';
$pink2 = '#f06292';
function esc($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function safe_img($file, $dir) {
    $path = $dir . basename($file);
    if ($file && preg_match('/^[a-zA-Z0-9_.-]+$/', $file) && file_exists($path)) {
        return $path;
    }
    return '';
}
?><!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>使用者 Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style nonce="<?=$csp_nonce?>">
        body { background: <?=$pink?>; }
        .main { background: #fff; border-radius: 10px; box-shadow: 0 0 10px #eee; padding:2rem; }
        .btn-pink { background: <?=$pink2?>; color:#fff; }
        .btn-pink:hover { background: #ad1457; }
    </style>
</head>
<body>
<div class="container py-4">
    <header class="row mb-3"><div class="col-12"><h1 class="text-center">使用者 Profile</h1></div></header>
    <main class="row justify-content-center"><div class="col-12 col-md-10 main">
        <form>
            <div class="mb-3">
                <label class="form-label">使用者ID</label>
                <input type="text" class="form-control" value="<?=esc($data['userid'])?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">使用者名稱</label>
                <input type="text" class="form-control" value="<?=esc($data['username'])?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">出生年</label>
                <input type="text" class="form-control" value="<?=esc($data['birthyear'])?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">性別</label>
                <input type="text" class="form-control" value="<?php
                    if ($data['gender']==='male') echo '生理男';
                    elseif ($data['gender']==='female') echo '生理女';
                    else echo '其他';
                ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">照片</label><br>
                <?php if ($data['photo'] && safe_img($data['photo'],$upload_dir)): ?>
                    <img src="<?=esc(safe_img($data['photo'],$upload_dir))?>" alt="照片" class="img-thumbnail" style="max-width:120px;">
                <?php else: ?>
                    <span class="text-muted">無</span>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">IG</label>
                <input type="text" class="form-control" value="<?=esc($data['ig'])?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">FB</label>
                <input type="text" class="form-control" value="<?=esc($data['fb'])?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">自我介紹</label>
                <textarea class="form-control" rows="3" readonly><?=esc($data['intro'])?></textarea>
            </div>
            <a href="edit.php" class="btn btn-pink">返回編輯</a>
        </form>
    </div></main>
    <footer class="row mt-4"><div class="col-12 text-center"><p>&copy; 2025 網站基礎技術與安全</p></div></footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>