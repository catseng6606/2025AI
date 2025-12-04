<?php
// edit.php

// 設定 cookie 安全屬性 (必須在 session_start 前)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
// 產生 CSP nonce
$csp_nonce = base64_encode(random_bytes(16));
// 設定 CSP header (允許 Bootstrap CDN 與字型，inline style/script 需 nonce，connect-src jsdelivr)
header("Content-Security-Policy: default-src 'self'; connect-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net 'nonce-$csp_nonce'; style-src 'self' https://cdn.jsdelivr.net 'nonce-$csp_nonce'; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net data:;");

// 讀取 .env 設定
$env = parse_ini_file(__DIR__.'/.env');
$upload_dir = $env['UPLOAD_DIR'] ?? 'uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// CSRF token 產生
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// 預設值
$data = $_SESSION['profile'] ?? [];
$data = array_merge([
    'userid' => '', 'username' => '', 'birthyear' => '', 'gender' => '',
    'photo' => '', 'ig' => '', 'fb' => '', 'intro' => ''
], $data);
$errors = [];

// 表單送出處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF 驗證
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'CSRF token 錯誤';
    }
    // 驗證規則
    $userid = $_POST['userid'] ?? '';
    $username = $_POST['username'] ?? '';
    $birthyear = $_POST['birthyear'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $ig = $_POST['ig'] ?? '';
    $fb = $_POST['fb'] ?? '';
    $intro = $_POST['intro'] ?? '';
    // 驗證
    if (!preg_match('/^[a-zA-Z0-9]+$/', $userid)) $errors[] = '使用者ID格式錯誤';
    if (!preg_match('/^[a-zA-Z\x{4e00}-\x{9fa5}\s]+$/u', $username)) $errors[] = '名稱格式錯誤';
    if (!preg_match('/^(19[0-9]{2}|20[0-9]{2}|2100)$/', $birthyear)) $errors[] = '出生年格式錯誤';
    if (!in_array($gender, ['male','female','other'])) $errors[] = '性別錯誤';
    if ($ig && !preg_match('/^(https?:\/\/)?(www\.)?instagram\.com\/[A-Za-z0-9_.]+$/', $ig)) $errors[] = 'IG格式錯誤';
    if ($fb && !preg_match('/^(https?:\/\/)?(www\.)?facebook\.com\/[A-Za-z0-9.]+$/', $fb)) $errors[] = 'FB格式錯誤';
    // 圖片處理
    $photo = $data['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
        // PHP 8.5+ 不需 finfo_close
        $allow = ['image/jpeg','image/png','image/gif'];
        if (!in_array($mime, $allow)) {
            $errors[] = '僅允許上傳 jpg/png/gif 圖片';
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_',true).'.'.$ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir.$filename);
            $photo = $filename;
        }
    }
    // 自我介紹清洗
    $intro = strip_tags($intro);
    // 無錯誤則存入 session
    if (!$errors) {
        $_SESSION['profile'] = [
            'userid'=>$userid, 'username'=>$username, 'birthyear'=>$birthyear,
            'gender'=>$gender, 'photo'=>$photo, 'ig'=>$ig, 'fb'=>$fb, 'intro'=>$intro
        ];
        // 確保未有任何輸出再執行 header
        if (!headers_sent()) {
            header('Location: profile.php');
            exit;
        } else {
            echo '<script>location.href="profile.php";</script>';
            exit;
        }
    }
    // 保留輸入值
    $data = compact('userid','username','birthyear','gender','photo','ig','fb','intro');
}

// 色系
$pink = '#f8bbd0';
$pink2 = '#f06292';
?><!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>使用者 Profile 編輯</title>
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
    <header class="row mb-3"><div class="col-12"><h1 class="text-center">使用者 Profile 編輯</h1></div></header>
    <main class="row justify-content-center"><div class="col-12 col-md-10 main">
        <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?=implode('<br>', array_map('htmlspecialchars', $errors))?>
        </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="userid" class="form-label">使用者ID</label>
                <input type="text" class="form-control" id="userid" name="userid" pattern="^[a-zA-Z0-9]+$" required value="<?=htmlspecialchars($data['userid']??'',ENT_QUOTES)?>">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">使用者名稱</label>
                <input type="text" class="form-control" id="username" name="username" pattern="^[a-zA-Z\x{4e00}-\x{9fa5}\s]+$" required value="<?=htmlspecialchars($data['username']??'',ENT_QUOTES)?>">
            </div>
            <div class="mb-3">
                <label for="birthyear" class="form-label">出生年</label>
                <input type="number" class="form-control" id="birthyear" name="birthyear" min="1900" max="2100" required value="<?=htmlspecialchars($data['birthyear']??'',ENT_QUOTES)?>">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">性別</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="">請選擇</option>
                    <option value="male" <?=$data['gender']==='male'?'selected':''?>>生理男</option>
                    <option value="female" <?=$data['gender']==='female'?'selected':''?>>生理女</option>
                    <option value="other" <?=$data['gender']==='other'?'selected':''?>>其他</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">照片</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                <?php if ($data['photo']): ?>
                    <img src="<?=$upload_dir.htmlspecialchars($data['photo'],ENT_QUOTES)?>" alt="目前照片" class="img-thumbnail mt-2" style="max-width:120px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="ig" class="form-label">IG</label>
                <input type="text" class="form-control" id="ig" name="ig" pattern="^(https?:\/\/)?(www\.)?instagram\.com\/[A-Za-z0-9_.]+$" value="<?=htmlspecialchars($data['ig']??'',ENT_QUOTES)?>">
            </div>
            <div class="mb-3">
                <label for="fb" class="form-label">FB</label>
                <input type="text" class="form-control" id="fb" name="fb" pattern="^(https?:\/\/)?(www\.)?facebook\.com\/[A-Za-z0-9.]+$" value="<?=htmlspecialchars($data['fb']??'',ENT_QUOTES)?>">
            </div>
            <div class="mb-3">
                <label for="intro" class="form-label">自我介紹</label>
                <textarea class="form-control" id="intro" name="intro" rows="3"><?=htmlspecialchars($data['intro']??'',ENT_QUOTES)?></textarea>
            </div>
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf_token,ENT_QUOTES)?>">
            <button type="submit" class="btn btn-pink">提交</button>
        </form>
    </div></main>
    <footer class="row mt-4"><div class="col-12 text-center"><p>&copy; 2025 網站基礎技術與安全</p></div></footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script nonce="<?=$csp_nonce?>">
// Bootstrap 驗證
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
</body>
</html>