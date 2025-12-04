<?php
require_once 'config.php';

// 檢查是否已登入 (此作業未要求登入系統，但為了 Session 運作，需確保 Session 已啟動)
// config.php 中已啟動 Session

$errors = [];
$success = false;

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF 驗證
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // 2. 接收並驗證資料
    $user_id = trim($_POST['user_id'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $birth_year = trim($_POST['birth_year'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $ig_url = trim($_POST['ig_url'] ?? '');
    $fb_url = trim($_POST['fb_url'] ?? '');
    $intro = trim($_POST['intro'] ?? '');

    // 驗證 User ID (僅英數字)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $user_id)) {
        $errors[] = '使用者ID只能包含英數字';
    }

    // 驗證 Username (英文字母、空白、UTF-8中文)
    // \p{Han} 匹配漢字, \p{L} 匹配任何語言的字母 (包含英文)
    if (!preg_match('/^[\p{L}\s]+$/u', $username)) {
        $errors[] = '使用者名稱只能包含英文字母、空白與中文';
    }

    // 驗證出生年 (1900-2100)
    if (!is_numeric($birth_year) || $birth_year < 1900 || $birth_year > 2100) {
        $errors[] = '出生年份必須在 1900 到 2100 之間';
    }

    // 驗證性別
    $allowed_genders = ['male', 'female', 'other'];
    if (!in_array($gender, $allowed_genders)) {
        $errors[] = '請選擇有效的性別';
    }

    // 驗證 IG URL
    if (!empty($ig_url) && !preg_match('/^https?:\/\/(www\.)?instagram\.com\/[a-zA-Z0-9_.]+\/?$/', $ig_url)) {
        $errors[] = 'Instagram 網址格式不正確';
    }

    // 驗證 FB URL
    if (!empty($fb_url) && !preg_match('/^https?:\/\/(www\.)?facebook\.com\/[a-zA-Z0-9.]+\/?$/', $fb_url)) {
        $errors[] = 'Facebook 網址格式不正確';
    }

    // 3. 處理檔案上傳
    $photo_path = $_SESSION['user']['photo'] ?? ''; // 保留舊照片
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        // 驗證 MIME 類型
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);

        if (!in_array($mime_type, $allowed_types)) {
            $errors[] = '只允許上傳 JPG, PNG, GIF 格式的圖片';
        } else {
            // 確保上傳目錄存在
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // 產生唯一檔名
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('user_', true) . '.' . $ext;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $photo_path = $destination;
            } else {
                $errors[] = '圖片上傳失敗';
            }
        }
    }

    // 4. 若無錯誤，儲存至 Session 並轉址
    if (empty($errors)) {
        // 清洗自我介紹 (雖然輸出時會 escape，但這裡可以做簡單的處理或保留原樣由輸出端處理)
        // 這裡選擇保留原樣，輸出時使用 htmlspecialchars

        $_SESSION['user'] = [
            'id' => $user_id,
            'name' => $username,
            'birth_year' => $birth_year,
            'gender' => $gender,
            'photo' => $photo_path,
            'ig' => $ig_url,
            'fb' => $fb_url,
            'intro' => $intro
        ];

        header('Location: profile.php');
        exit;
    }
}

// 讀取現有資料 (如果有的話)
$user = $_SESSION['user'] ?? [];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯個人資料</title>
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

        .form-control:focus,
        .form-select:focus {
            border-color: #f06292;
            box-shadow: 0 0 0 0.25rem rgba(240, 98, 146, 0.25);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <header class="mb-4 text-center">
            <h1>編輯個人資料</h1>
        </header>

        <main class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">個人資料表單</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="edit.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <div class="mb-3">
                                <label for="user_id" class="form-label">使用者 ID (僅限英數字)</label>
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>" required
                                    pattern="[a-zA-Z0-9]+">
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">使用者名稱 (英文字母、空白、中文)</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="birth_year" class="form-label">出生年份 (1900-2100)</label>
                                <input type="number" class="form-control" id="birth_year" name="birth_year"
                                    value="<?php echo htmlspecialchars($user['birth_year'] ?? ''); ?>" min="1900"
                                    max="2100" required>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">性別</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" disabled <?php echo empty($user['gender']) ? 'selected' : ''; ?>>
                                        請選擇</option>
                                    <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>生理男</option>
                                    <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>生理女</option>
                                    <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>其他</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">使用者照片</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                <?php if (!empty($user['photo'])): ?>
                                    <div class="mt-2">
                                        <small>目前照片:</small><br>
                                        <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Current Photo"
                                            style="max-height: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="ig_url" class="form-label">Instagram 連結</label>
                                <input type="url" class="form-control" id="ig_url" name="ig_url"
                                    value="<?php echo htmlspecialchars($user['ig'] ?? ''); ?>"
                                    placeholder="https://instagram.com/username">
                            </div>

                            <div class="mb-3">
                                <label for="fb_url" class="form-label">Facebook 連結</label>
                                <input type="url" class="form-control" id="fb_url" name="fb_url"
                                    value="<?php echo htmlspecialchars($user['fb'] ?? ''); ?>"
                                    placeholder="https://facebook.com/username">
                            </div>

                            <div class="mb-3">
                                <label for="intro" class="form-label">自我介紹</label>
                                <textarea class="form-control" id="intro" name="intro"
                                    rows="4"><?php echo htmlspecialchars($user['intro'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">提交資料</button>
                            </div>
                        </form>
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