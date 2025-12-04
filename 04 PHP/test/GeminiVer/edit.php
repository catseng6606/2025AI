<?php
require_once 'config.php';

// 儲存錯誤訊息
$errors = [];
// 儲存表單資料
$inputs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 驗證 CSRF Token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $errors['csrf'] = 'CSRF 驗證失敗，請重試。';
    }

    // 2. 接收並驗證表單資料
    $inputs['user_id'] = $_POST['user_id'] ?? '';
    if (!preg_match('/^[a-zA-Z0-9]+$/', $inputs['user_id'])) {
        $errors['user_id'] = '使用者ID只能是英數字。';
    }

    $inputs['user_name'] = $_POST['user_name'] ?? '';
    if (!preg_match('/^[a-zA-Z\s\p{Han}]+$/u', $inputs['user_name'])) {
        $errors['user_name'] = '使用者名稱只能包含英文字母、空白與UTF-8中文。';
    }

    $inputs['user_birth_year'] = $_POST['user_birth_year'] ?? '';
    if (!filter_var($inputs['user_birth_year'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1900, 'max_range' => 2100]
    ])) {
        $errors['user_birth_year'] = '出生年份必須是 1900 到 2100 之間的數字。';
    }

    $inputs['user_gender'] = $_POST['user_gender'] ?? '';
    if (!in_array($inputs['user_gender'], ['male', 'female', 'other'])) {
        $errors['user_gender'] = '請選擇有效的性別。';
    }

    $inputs['user_ig'] = $_POST['user_ig'] ?? '';
    if ($inputs['user_ig'] && !preg_match('/^(https:\/\/www\.instagram\.com\/)?[a-zA-Z0-9._]+\/?$/', $inputs['user_ig'])) {
        $errors['user_ig'] = '請輸入有效的 Instagram 網址或使用者名稱。';
    }

    $inputs['user_fb'] = $_POST['user_fb'] ?? '';
    if ($inputs['user_fb'] && !preg_match('/^(https:\/\/www\.facebook\.com\/)?[a-zA-Z0-9._-]+\/?$/', $inputs['user_fb'])) {
        $errors['user_fb'] = '請輸入有效的 Facebook 網址或使用者ID。';
    }

    $inputs['user_intro'] = $_POST['user_intro'] ?? '';
    // 自我介紹直接儲存，輸出時清洗

    // 3. 處理檔案上傳
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['user_photo'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($photo['type'], $allowed_types)) {
            $errors['user_photo'] = '只允許上傳 JPG, PNG, GIF, WEBP 格式的圖片。';
        } else {
            $upload_dir = rtrim(getenv('UPLOAD_DIR') ?: 'uploads', '/') . '/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $filename = 'user_' . uniqid() . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
            $filepath = $upload_dir . $filename;

            if (move_uploaded_file($photo['tmp_name'], $filepath)) {
                $inputs['user_photo'] = $filepath;
            } else {
                $errors['user_photo'] = '檔案上傳失敗。';
            }
        }
    } else {
         // 如果沒有上傳新照片，保留舊照片路徑（如果有的話）
        $inputs['user_photo'] = $_SESSION['profile']['user_photo'] ?? '';
    }


    // 4. 如果沒有錯誤，儲存到 Session 並轉址
    if (empty($errors)) {
        $_SESSION['profile'] = $inputs;
        header('Location: profile.php');
        exit;
    }
}

// 產生新的 CSRF Token 給表單使用
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯個人資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fce4ec; /* 粉色系背景 */
        }
        .container {
            max-width: 800px;
        }
        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #f8bbd0; /* 較深的粉色 */
            color: #424242;
            border-bottom: none;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .btn-primary {
            background-color: #ec407a;
            border-color: #ec407a;
        }
        .btn-primary:hover {
            background-color: #d81b60;
            border-color: #d81b60;
        }
        .form-control:focus {
            border-color: #f8bbd0;
            box-shadow: 0 0 0 0.25rem rgba(248, 187, 208, 0.5);
        }
        footer {
            background-color: #f8bbd0;
            color: #424242;
        }
    </style>
</head>
<body>
    <header class="text-center py-4">
        <h1>編輯個人資料</h1>
    </header>

    <main class="container my-5">
        <div class="card">
            <div class="card-header text-center">
                <h2>請填寫您的資料</h2>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="profileForm" action="edit.php" method="POST" enctype="multipart/form-data" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

                    <div class="mb-3">
                        <label for="user_id" class="form-label">使用者ID</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" value="<?= e($inputs['user_id'] ?? $_SESSION['profile']['user_id'] ?? '') ?>" required pattern="^[a-zA-Z0-9]+$">
                        <div class="invalid-feedback">使用者ID只能是英數字。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_name" class="form-label">使用者名稱</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" value="<?= e($inputs['user_name'] ?? $_SESSION['profile']['user_name'] ?? '') ?>" required pattern="^[a-zA-Z\s\p{Han}]+$">
                        <div class="invalid-feedback">使用者名稱只能包含英文字母、空白與UTF-8中文。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_birth_year" class="form-label">出生年份</label>
                        <input type="number" class="form-control" id="user_birth_year" name="user_birth_year" value="<?= e($inputs['user_birth_year'] ?? $_SESSION['profile']['user_birth_year'] ?? '') ?>" required min="1900" max="2100">
                        <div class="invalid-feedback">出生年份必須是 1900 到 2100 之間的數字。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_gender" class="form-label">性別</label>
                        <select class="form-select" id="user_gender" name="user_gender" required>
                            <option value="" disabled <?= !isset($_SESSION['profile']['user_gender']) ? 'selected' : '' ?>>請選擇</option>
                            <option value="male" <?= (($inputs['user_gender'] ?? $_SESSION['profile']['user_gender'] ?? '') === 'male') ? 'selected' : '' ?>>生理男</option>
                            <option value="female" <?= (($inputs['user_gender'] ?? $_SESSION['profile']['user_gender'] ?? '') === 'female') ? 'selected' : '' ?>>生理女</option>
                            <option value="other" <?= (($inputs['user_gender'] ?? $_SESSION['profile']['user_gender'] ?? '') === 'other') ? 'selected' : '' ?>>其他</option>
                        </select>
                        <div class="invalid-feedback">請選擇一個有效的性別。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_photo" class="form-label">使用者照片</label>
                        <input class="form-control" type="file" id="user_photo" name="user_photo" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">目前照片: <?= e($_SESSION['profile']['user_photo'] ?? '無') ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="user_ig" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="user_ig" name="user_ig" value="<?= e($inputs['user_ig'] ?? $_SESSION['profile']['user_ig'] ?? '') ?>" pattern="^(https:\/\/www\.instagram\.com\/)?[a-zA-Z0-9._]+\/?$">
                        <div class="invalid-feedback">請輸入有效的 Instagram 網址或使用者名稱。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_fb" class="form-label">Facebook</label>
                        <input type="text" class="form-control" id="user_fb" name="user_fb" value="<?= e($inputs['user_fb'] ?? $_SESSION['profile']['user_fb'] ?? '') ?>" pattern="^(https:\/\/www\.facebook\.com\/)?[a-zA-Z0-9._-]+\/?$">
                        <div class="invalid-feedback">請輸入有效的 Facebook 網址或使用者ID。</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_intro" class="form-label">自我介紹</label>
                        <textarea class="form-control" id="user_intro" name="user_intro" rows="4"><?= e($inputs['user_intro'] ?? $_SESSION['profile']['user_intro'] ?? '') ?></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">提交資料</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="text-center py-3 mt-5">
        <p>&copy; 2025 AI Class. All Rights Reserved.</p>
    </footer>

    <script>
    // JavaScript for disabling form submissions if there are invalid fields
    (function () {
      'use strict'

      var form = document.getElementById('profileForm')
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })()
    </script>
</body>
</html>
