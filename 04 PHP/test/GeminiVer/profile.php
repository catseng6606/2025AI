<?php
require_once 'config.php';

// 如果 Session 中沒有資料，轉址回編輯頁面
if (!isset($_SESSION['profile'])) {
    header('Location: edit.php');
    exit;
}

$profile = $_SESSION['profile'];

$gender_map = [
    'male' => '生理男',
    'female' => '生理女',
    'other' => '其他',
];

?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人資料</title>
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
            overflow: hidden; /* 確保圖片圓角 */
        }
        .card-header {
            background-color: #f8bbd0; /* 較深的粉色 */
            color: #424242;
            border-bottom: none;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            margin-top: -75px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .profile-intro {
            background-color: #fef9fa;
            border: 1px solid #fce4ec;
            border-radius: 8px;
        }
        .list-group-item {
            background-color: transparent;
            border-color: #fce4ec;
        }
        .btn-primary {
            background-color: #ec407a;
            border-color: #ec407a;
        }
        .btn-primary:hover {
            background-color: #d81b60;
            border-color: #d81b60;
        }
        footer {
            background-color: #f8bbd0;
            color: #424242;
        }
    </style>
</head>
<body>
    <header class="text-center py-4">
        <h1>個人資料</h1>
    </header>

    <main class="container my-5">
        <div class="card">
            <div class="card-header" style="height: 120px;"></div>
            <div class="card-body text-center" style="margin-top: -75px;">
                <?php if (!empty($profile['user_photo']) && file_exists($profile['user_photo'])): ?>
                    <img src="<?= e($profile['user_photo']) ?>" alt="使用者照片" class="profile-img">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="預設照片" class="profile-img">
                <?php endif; ?>

                <h2 class="mt-3"><?= e($profile['user_name']) ?></h2>
                <p class="text-muted"><?= e($profile['user_id']) ?></p>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>出生年份</strong>
                        <span><?= e($profile['user_birth_year']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>性別</strong>
                        <span><?= e($gender_map[$profile['user_gender']] ?? '未指定') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Instagram</strong>
                        <span><a href="https://www.instagram.com/<?= e(basename($profile['user_ig'])) ?>" target="_blank"><?= e($profile['user_ig']) ?></a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Facebook</strong>
                        <span><a href="https://www.facebook.com/<?= e(basename($profile['user_fb'])) ?>" target="_blank"><?= e($profile['user_fb']) ?></a></span>
                    </li>
                </ul>

                <h5 class="mt-4">自我介紹</h5>
                <div class="p-3 profile-intro">
                    <p><?= clean_html($profile['user_intro']) ?></p>
                </div>

                <div class="text-center mt-4">
                    <a href="edit.php" class="btn btn-primary">編輯資料</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-3 mt-5">
        <p>&copy; 2025 AI Class. All Rights Reserved.</p>
    </footer>
</body>
</html>
