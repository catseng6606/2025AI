# PHP 安全程式：環境與元件安全 (Environment & Components)

本文件介紹如何確保 PHP 應用程式的執行環境安全，以及如何管理第三方元件的安全性。

---

## 1. 使用安全的元件與依賴管理 (Composer)

現代開發很少從零開始，我們會用很多套件（如 Laravel, PHPUnit, Monolog 等）。

### 風險

如果你用的套件有已知漏洞（CVE, Common Vulnerabilities and Exposures），你的城堡就會有暗門。駭客可以利用這些已知的漏洞直接攻擊你的應用程式，而不需要尋找你程式碼中的錯誤。

### 防禦方式

1.  **定期更新**：定期執行 `composer update` 來更新套件到最新版本。
2.  **安全審計**：使用 `composer audit` (Composer 2.4+) 檢查已安裝套件的已知漏洞報告。

```bash
# 更新所有依賴套件
composer update

# 檢查是否有已知的安全漏洞
composer audit
```

**範例輸出：**

```text
Found 1 security vulnerability advisory affecting 1 package:
+-------------------+----------------------------------------------------------------------------------+
| Package           | symfony/http-kernel                                                              |
| CVE               | CVE-2022-xxxx                                                                    |
| Title             | Potential open redirect vulnerability                                            |
| URL               | https://symfony.com/cve-2022-xxxx                                                |
| Affected versions | >=5.0.0,<5.4.20                                                                  |
| Reported at       | 2022-11-01T00:00:00+00:00                                                        |
+-------------------+----------------------------------------------------------------------------------+
```

---

## 2. 敏感資料外洩 (Sensitive Data Exposure)

### 風險

1.  **原始碼洩漏**：把資料庫密碼、API Key、AWS Secret 直接寫在 PHP 檔案裡。如果設定不當（例如 Git 目錄被公開），原始碼被看光，所有機密也就曝光了。
2.  **錯誤訊息洩漏**：在生產環境開啟錯誤顯示。錯誤訊息（如資料庫連接錯誤）會洩漏你的檔案路徑、資料庫使用者名稱、SQL 結構等資訊。

### 防禦方式

#### A. 使用 .env 檔案管理機密

使用 `.env` 檔案存放敏感資訊，並確保 `.env` **絕對不能**被上傳到 Git 或被網頁直接讀取。

1.  **安裝 phpdotenv** (如果未使用框架)：
    ```bash
    composer require vlucas/phpdotenv
    ```
2.  **建立 .env 檔案** (加入 `.gitignore`)：
    ```ini
    DB_HOST=localhost
    DB_USER=root
    DB_PASS=SuperSecretPassword!
    API_KEY=1234567890abcdef
    ```
3.  **在 PHP 中讀取**：

    ```php
    <?php
    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // 使用 getenv 或 $_ENV 讀取
    $db_pass = $_ENV['DB_PASS'];
    ?>
    ```

#### B. 關閉生產環境的 display_errors

在 `php.ini` 或程式入口設定，確保錯誤訊息只記錄在 Log 檔中，而不顯示給使用者。

**php.ini 設定：**

```ini
; 生產環境建議設定
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
```

**PHP 程式碼設定 (Runtime)：**

```php
<?php
// 在生產環境中
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); // 仍然報告錯誤，但只記錄到 Log
?>
```
