<?php
require_once 'config.php';

// 設定 CSP Header
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data:;");

// 讀取 Session 資料
$user = $_SESSION['user'] ?? null;

if (!$user) {
    // 若無資料，導回編輯頁面
    header('Location: edit.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人資料頁面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fce4ec;
            /* Pink 50 */
            color: #880e4f;
            /* Pink 900 */
        }

        .card {
            border-color: #f48fb1;
            /* Pink 200 */
        }

        .card-header {
            background-color: #f06292;
            /* Pink 300 */
            color: white;
        }

        .btn-primary {
            background-color: #ec407a;
            /* Pink 400 */
            border-color: #ec407a;
        }

        .btn-primary:hover {
            background-color: #d81b60;
            /* Pink 600 */
            border-color: #d81b60;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <header class="mb-4 text-center">
            <h1>個人資料展示</h1>
        </header>

        <main class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">使用者資訊</h5>
                        <a href="edit.php" class="btn btn-sm btn-light text-primary">編輯資料</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <?php if (!empty($user['photo'])): ?>
                                    <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="User Photo"
                                        class="img-fluid rounded-circle border border-3 border-white shadow"
                                        style="width: 200px; height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white"
                                        style="width: 200px; height: 200px; font-size: 4rem;">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <dl class="row">
                                    <dt class="col-sm-3">ID</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($user['id']); ?></dd>

                                    <dt class="col-sm-3">姓名</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($user['name']); ?></dd>

                                    <dt class="col-sm-3">出生年份</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($user['birth_year']); ?></dd>

                                    <dt class="col-sm-3">性別</dt>
                                    <dd class="col-sm-9">
                                        <?php
                                        $genders = ['male' => '生理男', 'female' => '生理女', 'other' => '其他'];
                                        echo htmlspecialchars($genders[$user['gender']] ?? $user['gender']);
                                        ?>
                                    </dd>

                                    <dt class="col-sm-3">社群連結</dt>
                                    <dd class="col-sm-9">
                                        <?php if (!empty($user['ig'])): ?>
                                            <a href="<?php echo htmlspecialchars($user['ig']); ?>" target="_blank"
                                                class="text-decoration-none me-2">Instagram</a>
                                        <?php endif; ?>
                                        <?php if (!empty($user['fb'])): ?>
                                            <a href="<?php echo htmlspecialchars($user['fb']); ?>" target="_blank"
                                                class="text-decoration-none">Facebook</a>
                                        <?php endif; ?>
                                        <?php if (empty($user['ig']) && empty($user['fb'])): ?>
                                            <span class="text-muted">無</span>
                                        <?php endif; ?>
                                    </dd>

                                    <dt class="col-sm-3">自我介紹</dt>
                                    <dd class="col-sm-9">
                                        <div class="p-3 bg-light rounded">
                                            <?php echo nl2br(htmlspecialchars($user['intro'])); ?>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="mt-5 text-center text-muted">
            <p>&copy; <?php echo date('Y'); ?> User Profile System. All rights reserved.</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>