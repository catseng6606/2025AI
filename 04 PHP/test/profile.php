<?php
require_once 'config.php';

// 檢查是否有資料
if (!isset($_SESSION['user'])) {
    header('Location: edit.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人檔案</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSP Header -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data:;">
    <style>
        :root {
            --primary-pink: #ffb7b2;
            --secondary-pink: #ffdac1;
            --text-color: #555;
        }

        body {
            background-color: #fff0f5;
            color: var(--text-color);
        }

        .header {
            background-color: var(--primary-pink);
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 0 0 10px 10px;
        }

        .footer {
            background-color: var(--primary-pink);
            padding: 10px;
            text-align: center;
            margin-top: 20px;
            border-radius: 10px 10px 0 0;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--secondary-pink);
        }

        .btn-primary {
            background-color: #ff69b4;
            border-color: #ff69b4;
        }

        .btn-primary:hover {
            background-color: #ff1493;
            border-color: #ff1493;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <!-- Header -->
            <div class="col-12 header">
                <h1>個人檔案</h1>
            </div>

            <!-- Main -->
            <div class="col-12 main">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card p-4">
                            <div class="text-center mb-4">
                                <?php if (!empty($user['photo'])): ?>
                                    <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo"
                                        class="profile-img">
                                <?php else: ?>
                                    <div class="alert alert-secondary d-inline-block">未上傳照片</div>
                                <?php endif; ?>
                            </div>

                            <dl class="row">
                                <dt class="col-sm-3">使用者ID</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($user['id']); ?></dd>

                                <dt class="col-sm-3">使用者名稱</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($user['name']); ?></dd>

                                <dt class="col-sm-3">出生年份</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($user['birthyear']); ?></dd>

                                <dt class="col-sm-3">性別</dt>
                                <dd class="col-sm-9">
                                    <?php
                                    $genders = ['male' => '生理男', 'female' => '生理女', 'other' => '其他'];
                                    echo htmlspecialchars($genders[$user['gender']] ?? $user['gender']);
                                    ?>
                                </dd>

                                <dt class="col-sm-3">Instagram</dt>
                                <dd class="col-sm-9">
                                    <?php if (!empty($user['ig'])): ?>
                                        <a href="<?php echo htmlspecialchars($user['ig']); ?>"
                                            target="_blank"><?php echo htmlspecialchars($user['ig']); ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </dd>

                                <dt class="col-sm-3">Facebook</dt>
                                <dd class="col-sm-9">
                                    <?php if (!empty($user['fb'])): ?>
                                        <a href="<?php echo htmlspecialchars($user['fb']); ?>"
                                            target="_blank"><?php echo htmlspecialchars($user['fb']); ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </dd>

                                <dt class="col-sm-3">自我介紹</dt>
                                <dd class="col-sm-9">
                                    <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                                </dd>
                            </dl>

                            <div class="text-center mt-4">
                                <a href="edit.php" class="btn btn-primary">返回編輯</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="col-12 footer">
                <p>&copy; 2025 User Profile System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>