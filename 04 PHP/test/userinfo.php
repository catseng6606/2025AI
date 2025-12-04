<?php
function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

function safe_url($url) {
    return (preg_match('/^https:\/\/[\w.-]+/', $url)) ? $url : '';
}

$fields = [
    'user_id' => '',
    'name' => '',
    'email' => '',
    'age' => '',
    'bio' => '',
    'img' => '',
    'social' => ''
];

$csrf_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
        $csrf_msg = '<div class="alert alert-danger">CSRF 驗證失敗</div>';
    } else {
        foreach ($fields as $k => $v) {
            if (isset($_POST[$k])) {
                $fields[$k] = htmlspecialchars(trim($_POST[$k]), ENT_QUOTES, 'UTF-8');
            }
        }
        setcookie('user_id', $fields['user_id'], time() + 3600, '/', '', false, true);
        $_SESSION['profile'] = $fields;
    }
}
elseif (isset($_SESSION['profile'])) {
    $fields = $_SESSION['profile'];
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>使用者 Profile</title>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' https:; style-src 'self' 'unsafe-inline'; script-src 'self' https://cdn.jsdelivr.net;"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #ffe4ec; }
        .card { background: #fff0f6; border: 1px solid #f8bbd0; }
        .btn-pink { background: #f06292; color: white; }
        .form-label { color: #d81b60; }
        h1 { color: #d81b60; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">使用者 Profile</h1>
        <form method="post" class="mb-4">
            <div class="card shadow-lg rounded-4 p-4 mb-4" style="background:#fff0f6; border:1px solid #f8bbd0;">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">User ID</label>
                        <input type="text" name="user_id" class="form-control rounded-pill border-pink" value="<?php echo $fields['user_id']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">使用者名稱</label>
                        <input type="text" name="name" class="form-control rounded-pill border-pink" value="<?php echo $fields['name']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">電子郵件</label>
                        <input type="email" name="email" class="form-control rounded-pill border-pink" value="<?php echo $fields['email']; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">年齡</label>
                        <input type="number" name="age" class="form-control rounded-pill border-pink" min="0" value="<?php echo $fields['age']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">個人經歷</label>
                        <textarea name="bio" class="form-control rounded-3 border-pink" rows="2"><?php echo $fields['bio']; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">圖片連結</label>
                        <input type="url" name="img" class="form-control rounded-pill border-pink" value="<?php echo $fields['img']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">FB 或 IG 連結</label>
                        <input type="url" name="social" class="form-control rounded-pill border-pink" value="<?php echo $fields['social']; ?>">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-pink px-5 py-2 rounded-pill shadow-sm" style="font-size:1.2em;">儲存</button>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <?php if (!empty($fields['user_id'])): ?>
        <div class="card p-4 mb-3">
            <h4 class="mb-3">個人資訊</h4>
            <ul class="list-unstyled">
                <li><strong>User ID:</strong> <?php echo htmlspecialchars($_COOKIE['user_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>使用者名稱:</strong> <?php echo $fields['name']; ?></li>
                <li><strong>電子郵件:</strong> <?php echo $fields['email']; ?></li>
                <li><strong>年齡:</strong> <?php echo $fields['age']; ?></li>
                <li><strong>個人經歷:</strong> <?php echo $fields['bio']; ?></li>
                <li><strong>圖片:</strong>
                    <?php if ($fields['img']): ?>
                        <img src="<?php echo safe_url($fields['img']); ?>" alt="Profile" style="max-width:120px;max-height:120px;">
                    <?php endif; ?>
                </li>
                <li><strong>社群連結:</strong>
                    <?php if ($fields['social'] && safe_url($fields['social'])): ?>
                        <a href="<?php echo safe_url($fields['social']); ?>" target="_blank" style="color:#d81b60;"><?php echo $fields['social']; ?></a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>