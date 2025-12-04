<?php
require_once 'config.php';

$error = '';
$success = '';

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 驗證 CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF validation failed.');
    }

    // 取得並清洗輸入
    $id = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $name = trim($_POST['username'] ?? '');
    $birthyear = filter_input(INPUT_POST, 'birthyear', FILTER_VALIDATE_INT);
    $gender = $_POST['gender'] ?? '';
    $ig = trim($_POST['ig'] ?? '');
    $fb = trim($_POST['fb'] ?? '');
    $bio = trim($_POST['bio'] ?? ''); // 後續使用 htmlspecialchars 輸出

    // 驗證規則
    $isValid = true;
    $errorMsg = [];

    // ID: 英數字
    if (!preg_match('/^[a-zA-Z0-9]+$/', $id)) {
        $isValid = false;
        $errorMsg[] = '使用者ID只能包含英數字。';
    }

    // 名稱: 英文字母、空白與UTF-8中文
    // 使用 u 修飾符支援 UTF-8
    if (!preg_match('/^[\p{L}\s]+$/u', $name)) {
        $isValid = false;
        $errorMsg[] = '使用者名稱只能包含英文字母、空白與中文。';
    }

    // 出生年: 1900-2100
    if ($birthyear === false || $birthyear < 1900 || $birthyear > 2100) {
        $isValid = false;
        $errorMsg[] = '出生年份必須在 1900 到 2100 之間。';
    }

    // 性別
    $allowedGenders = ['male', 'female', 'other'];
    if (!in_array($gender, $allowedGenders)) {
        $isValid = false;
        $errorMsg[] = '請選擇有效的性別。';
    }

    // IG & FB (簡單驗證 URL)
    // 題目要求使用正則表達式驗證格式
    // 這裡假設需要是完整的 URL 格式
    $urlPattern = '/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)$/';

    if (!empty($ig) && !preg_match($urlPattern, $ig)) {
        $isValid = false;
        $errorMsg[] = 'IG 連結格式不正確。';
    }
    if (!empty($fb) && !preg_match($urlPattern, $fb)) {
        $isValid = false;
        $errorMsg[] = 'FB 連結格式不正確。';
    }

    // 檔案上傳
    $uploadedFile = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];

        // 驗證圖片格式 (MIME type)
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileTmpPath);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $isValid = false;
            $errorMsg[] = '只允許上傳 JPG, PNG, GIF 圖片。';
        } else {
            // 移動檔案
            // 確保目錄存在
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // 產生唯一檔名以防覆蓋與安全性
            $newFileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $uploadedFile = $destPath;
            } else {
                $isValid = false;
                $errorMsg[] = '檔案上傳失敗。';
            }
        }
    } else {
        // 如果沒有上傳新照片，保留舊的 (如果有的話)
        if (isset($_SESSION['user']['photo'])) {
            $uploadedFile = $_SESSION['user']['photo'];
        }
    }

    if ($isValid) {
        // 儲存至 Session
        $_SESSION['user'] = [
            'id' => $id,
            'name' => $name,
            'birthyear' => $birthyear,
            'gender' => $gender,
            'ig' => $ig,
            'fb' => $fb,
            'bio' => $bio, // 原始資料儲存，輸出時清洗
            'photo' => $uploadedFile
        ];

        // 轉址
        header('Location: profile.php');
        exit;
    } else {
        $error = implode('<br>', $errorMsg);
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯個人檔案</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSP Header (透過 meta tag 模擬，建議在 Server 端設定) -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data:;">
    <style>
        :root {
            --primary-pink: #ffb7b2;
            --secondary-pink: #ffdac1;
            --dark-pink: #e2f0cb;
            /* 這裡用一點綠色搭配，或者用深粉 */
            --text-color: #555;
        }

        body {
            background-color: #fff0f5;
            /* LavenderBlush */
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

        .btn-primary {
            background-color: #ff69b4;
            /* HotPink */
            border-color: #ff69b4;
        }

        .btn-primary:hover {
            background-color: #ff1493;
            /* DeepPink */
            border-color: #ff1493;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <!-- Header -->
            <div class="col-12 header">
                <h1>編輯個人檔案</h1>
            </div>

            <!-- Main -->
            <div class="col-12 main">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card p-4">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form action="edit.php" method="POST" enctype="multipart/form-data" class="needs-validation"
                                novalidate>
                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                                <div class="mb-3">
                                    <label for="userid" class="form-label">使用者ID (僅限英數字)</label>
                                    <input type="text" class="form-control" id="userid" name="userid"
                                        pattern="[a-zA-Z0-9]+" required
                                        value="<?php echo htmlspecialchars($_SESSION['user']['id'] ?? ''); ?>">
                                    <div class="invalid-feedback">請輸入有效的使用者ID (僅限英數字)。</div>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">使用者名稱 (英文字母、空白與中文)</label>
                                    <input type="text" class="form-control" id="username" name="username" required
                                        value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>">
                                    <div class="invalid-feedback">請輸入有效的使用者名稱。</div>
                                </div>

                                <div class="mb-3">
                                    <label for="birthyear" class="form-label">出生年份 (1900-2100)</label>
                                    <input type="number" class="form-control" id="birthyear" name="birthyear" min="1900"
                                        max="2100" required
                                        value="<?php echo htmlspecialchars($_SESSION['user']['birthyear'] ?? ''); ?>">
                                    <div class="invalid-feedback">請輸入 1900 到 2100 之間的年份。</div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">性別</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" disabled <?php echo empty($_SESSION['user']['gender']) ? 'selected' : ''; ?>>請選擇...</option>
                                        <option value="male" <?php echo (($_SESSION['user']['gender'] ?? '') === 'male') ? 'selected' : ''; ?>>生理男</option>
                                        <option value="female" <?php echo (($_SESSION['user']['gender'] ?? '') === 'female') ? 'selected' : ''; ?>>生理女</option>
                                        <option value="other" <?php echo (($_SESSION['user']['gender'] ?? '') === 'other') ? 'selected' : ''; ?>>其他</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">使用者照片</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <?php if (!empty($_SESSION['user']['photo'])): ?>
                                        <div class="mt-2">
                                            <small>目前照片: <a
                                                    href="<?php echo htmlspecialchars($_SESSION['user']['photo']); ?>"
                                                    target="_blank">查看</a></small>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="ig" class="form-label">Instagram 連結</label>
                                    <input type="url" class="form-control" id="ig" name="ig"
                                        placeholder="https://instagram.com/..."
                                        value="<?php echo htmlspecialchars($_SESSION['user']['ig'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="fb" class="form-label">Facebook 連結</label>
                                    <input type="url" class="form-control" id="fb" name="fb"
                                        placeholder="https://facebook.com/..."
                                        value="<?php echo htmlspecialchars($_SESSION['user']['fb'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">自我介紹</label>
                                    <textarea class="form-control" id="bio" name="bio"
                                        rows="4"><?php echo htmlspecialchars($_SESSION['user']['bio'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">提交</button>
                            </form>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>