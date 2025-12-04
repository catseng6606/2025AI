# 開發任務清單

## 階段一：基礎架構

- [ ] 建立 `GeminiVer` 資料夾
- [ ] 建立 `GeminiVer/edit.php`
- [ ] 建立 `GeminiVer/profile.php`
- [ ] 建立 `GeminiVer/config.php`
- [ ] 建立 `.env`

## 階段二：`edit.php` 開發

- [ ] **前端**
    - [ ] 引入 Bootstrap 5
    - [ ] 完成 Header, Main, Footer 粉色系版面
    - [ ] 建立使用者資料輸入表單
    - [ ] 實作 JavaScript 前端驗證
- [ ] **後端**
    - [ ] 在 `config.php` 中啟動 Session 並設定安全屬性
    - [ ] 在 `edit.php` 中引入 `config.php`
    - [ ] 產生並嵌入 CSRF Token
    - [ ] 處理 POST 請求
    - [ ] 驗證 CSRF Token
    - [ ] 驗證所有輸入欄位
    - [ ] 處理圖片上傳（驗證、移動）
    - [ ] 將資料存入 `$_SESSION`
    - [ ] 轉址到 `profile.php`

## 階段三：`profile.php` 開發

- [ ] **前端**
    - [ ] 引入 Bootstrap 5
    - [ ] 完成與 `edit.php` 風格一致的版面
    - [ ] 顯示使用者資料
- [ ] **後端**
    - [ ] 在 `profile.php` 中引入 `config.php`
    - [ ] 從 `$_SESSION` 讀取資料
    - [ ] 使用 `htmlspecialchars` 輸出所有資料
    - [ ] 正確顯示使用者圖片

## 階段四：安全性與測試

- [ ] 在 `config.php` 中設定 Content Security Policy (CSP)
- [ ] 在 `config.php` 中設定 `HttpOnly`, `Secure`, `SameSite` Cookie 屬性
- [ ] 審查程式碼，確保無安全性漏洞
- [ ] 準備手動測試案例與說明

## 階段五：完成

- [ ] 確認所有任務完成
- [ ] 交付程式碼
