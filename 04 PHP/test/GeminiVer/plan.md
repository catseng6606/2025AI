# 開發計畫

## 階段一：建立基礎架構

1.  **建立專案目錄**：建立 `GeminiVer` 資料夾來存放所有相關檔案。
2.  **建立核心檔案**：
    *   `edit.php`: 使用者個人資料修改頁面。
    *   `profile.php`: 使用者個人資料顯示頁面。
    *   `config.php`: 處理共享的設定，例如 Session 啟動、安全性標頭。
3.  **環境設定**：建立 `.env` 檔案，用於定義上傳目錄 `UPLOAD_DIR`。

## 階段二：開發 Profile 修改頁面 (`edit.php`)

1.  **版面布局**：
    *   使用 Bootstrap 5 實現三欄式佈局 (Header, Main, Footer)。
    *   主色系採用粉色系。
    *   Header 包含 `<h1>` 標題。
    *   Main 包含表單。
    *   Footer 包含 `&copy;` 版權文字。
2.  **表單開發**：
    *   建立包含以下欄位的 HTML 表單：
        *   使用者ID (`text`)
        *   使用者名稱 (`text`)
        *   使用者出生年 (`number`)
        *   使用者性別 (`select`)
        *   使用者照片 (`file`)
        *   使用者IG (`text`)
        *   使用者FB (`text`)
        *   使用者自我介紹 (`textarea`)
        *   CSRF Token (`hidden`)
        *   提交按鈕 (`button`)
3.  **客戶端驗證 (JavaScript)**：
    *   為所有輸入欄位添加即時驗證，確保規則與後端一致。
4.  **後端邏輯 (PHP)**：
    *   啟動 Session 並產生 CSRF token。
    *   當表單提交時：
        *   驗證 CSRF token。
        *   驗證所有輸入資料的格式與合理性。
        *   處理檔案上傳：驗證圖片格式、安全地移動檔案。
        *   將驗證通過的資料（包含圖片路徑）儲存於 `$_SESSION`。
        *   轉址到 `profile.php`。

## 階段三：開發 Profile 呈現頁面 (`profile.php`)

1.  **版面布局**：
    *   與 `edit.php` 風格保持一致。
2.  **資料呈現**：
    *   從 `$_SESSION` 讀取使用者資料。
    *   使用 `htmlspecialchars` 搭配 `ENT_QUOTES` 對所有輸出資料進行編碼，以防範 XSS 攻擊。
    *   特別處理自我介紹欄位，清洗可能有害的 HTML。
    *   顯示使用者上傳的照片。
3.  **安全性**：
    *   確保頁面正確引用 `config.php` 以設定安全性標頭。

## 階段四：安全性強化與測試

1.  **安全性標頭 (CSP)**：在 `config.php` 中設定 Content Security Policy (CSP) 來限制資源載入，減少 XSS 風險。
2.  **Session 設定**：設定 Session Cookie 的 `HttpOnly`、`Secure`、`SameSite` 屬性。
3.  **程式碼審查**：
    *   檢查所有輸入驗證是否確實執行。
    *   確認所有輸出都經過 `htmlspecialchars` 處理。
    *   確保 CSRF token 機制正確無誤。
4.  **手動測試**：提供手動測試步驟，由您親自驗證功能與安全性。

## 階段五：完成與交付

1.  **建立 `task.md`**：將上述開發步驟轉換為具體的任務清單。
2.  **提交程式碼**：將所有完成的程式碼交付給您。
