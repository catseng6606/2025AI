# PHP 安全程式：身分驗證與請求安全 (Auth & Request Security)

本文件介紹兩個最常見的 Web 安全漏洞：CSRF (跨站請求偽造) 與 失效的身分驗證 (Broken Authentication)，並以 PHP 為例說明防禦方式。

---

## 1. CSRF (跨站請求偽造)

這是最常見的漏洞之一。駭客誘導你點擊一個連結，那個連結會利用「你已經登入網站」的權限，偷偷幫你執行動作（例如：修改密碼、轉帳）。

### PHP 防禦方式：使用 CSRF Token

這就像是每次交易都要核對一個隨機生成的「動態口令」。

1.  伺服器產生一個隨機字串存入 `$_SESSION['csrf_token']`。
2.  將此字串放在表單的隱藏欄位中。
3.  使用者提交表單時，伺服器比對 `$_POST` 與 `$_SESSION` 中的 Token 是否一致。

### 範例程式碼

**產生 Token (在表單頁面)**

```php
<?php
session_start();

// 如果 Session 中沒有 Token，就產生一個新的
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<form action="process.php" method="POST">
    <!-- 將 Token 放入隱藏欄位 -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <label>新密碼：</label>
    <input type="password" name="new_password">
    <button type="submit">修改密碼</button>
</form>
```

**驗證 Token (在處理頁面 process.php)**

```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Token 不符，拒絕請求
        die('CSRF validation failed.');
    }

    // Token 驗證通過，執行業務邏輯
    // ...
    echo "密碼修改成功！";
}
?>
```

---

## 2. 失效的身分驗證 (Broken Authentication)

這涉及如何安全地管理 Session。

### 防護重點：登入後必須呼叫 `session_regenerate_id(true)`

**為什麼？**
這能防止「Session Fixation (會話固定攻擊)」，避免駭客預先設定一個 Session ID 讓你登入，進而接管你的帳號。

### 範例程式碼

**登入處理邏輯**

```php
<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// 假設驗證帳號密碼的函式
if (check_login($username, $password)) {
    // [關鍵] 登入成功後，立刻更換 Session ID，並刪除舊的 Session 檔案
    session_regenerate_id(true);

    // 設定登入狀態
    $_SESSION['user_id'] = $user_id;
    $_SESSION['logged_in'] = true;

    echo "登入成功，Session ID 已更新。";
} else {
    echo "帳號或密碼錯誤。";
}
?>
```
