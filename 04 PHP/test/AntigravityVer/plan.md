# 實作計畫 - PHP 作業

本計畫概述完成 PHP 作業的步驟，重點在於建立一個無需資料庫的安全使用者個人資料系統。

## 需要使用者審閱

> [!IMPORTANT]
> - **檔案上傳目錄**: 系統假設 `uploads/` 目錄存在或將被建立。請確保有寫入權限。
> - **Session 儲存**: 資料儲存在 `$_SESSION` 中。這是揮發性的，當 Session 過期或瀏覽器關閉時資料將會遺失。

## 預計變更

### 設定
#### [MODIFY] [.env](file:///d:/上課教材/2025AI/04 PHP/test/.env)
- 確保 `UPLOAD_DIR` 已定義。

### 前端與後端邏輯

#### [NEW] [edit.php](file:///d:/上課教材/2025AI/04 PHP/test/edit.php)
- **目的**: 使用者個人資料編輯表單。
- **功能**:
    - 引入 [config.php](file:///d:/%E4%B8%8A%E8%AA%B2%E6%95%99%E6%9D%90/2025AI/04%20PHP/test/config.php) 進行 Session 和環境設定。
    - Bootstrap 5 版面配置 (粉色系主題)。
    - 表單欄位:
        - ID (僅限英數字)
        - 名稱 (僅限英文字母、空白、UTF-8 中文)
        - 出生年 (數字, 1900-2100)
        - 性別 (下拉選單: 生理男, 生理女, 其他)
        - 照片 (檔案上傳, 圖片驗證)
        - IG/FB (使用正則表達式驗證 URL)
        - 自我介紹 (Textarea, 需清洗)
    - CSRF Token (隱藏欄位)。
    - POST 處理:
        - 伺服器端驗證需符合前端規則。
        - 檔案上傳處理 (移動至 `UPLOAD_DIR`)。
        - 將資料儲存至 `$_SESSION['user']`。
        - 成功後轉址至 [profile.php](file:///d:/%E4%B8%8A%E8%AA%B2%E6%95%99%E6%9D%90/2025AI/04%20PHP/test/userprofile.php)。

#### [NEW] [profile.php](file:///d:/上課教材/2025AI/04 PHP/test/profile.php)
- **目的**: 顯示使用者個人資料。
- **功能**:
    - 引入 [config.php](file:///d:/%E4%B8%8A%E8%AA%B2%E6%95%99%E6%9D%90/2025AI/04%20PHP/test/config.php)。
    - 設定 Content Security Policy (CSP) 標頭。
    - 顯示來自 `$_SESSION['user']` 的資料。
    - **安全性**: 所有輸出使用 `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`。
    - 版面配置: Bootstrap 5 (粉色系主題)，與 `edit.php` 一致。

## 驗證計畫

### 手動驗證
1.  **存取 `edit.php`**:
    - 檢查表單是否正確渲染粉色系主題。
    - 驗證 CSRF token 是否存在於隱藏欄位中。
2.  **提交無效資料**:
    - 嘗試提交非英數字的 ID。
    - 嘗試提交 1900-2100 範圍外的出生年。
    - 嘗試上傳非圖片檔案。
    - 驗證是否顯示錯誤訊息。
3.  **提交有效資料**:
    - 正確填寫所有欄位。
    - 上傳有效圖片。
    - 提交。
    - 驗證是否轉址至 [profile.php](file:///d:/%E4%B8%8A%E8%AA%B2%E6%95%99%E6%9D%90/2025AI/04%20PHP/test/userprofile.php)。
4.  **檢查 [profile.php](file:///d:/%E4%B8%8A%E8%AA%B2%E6%95%99%E6%9D%90/2025AI/04%20PHP/test/userprofile.php)**:
    - 驗證所有資料是否與提交相符。
    - 驗證圖片是否正確顯示。
    - 檢查頁面原始碼以確保 XSS 防護 (HTML 實體) 和 CSP 標頭。
5.  **安全性檢查**:
    - **XSS**: 嘗試在名稱或自我介紹中提交 `<script>alert(1)</script>`。驗證其顯示為文字而非執行。
    - **CSRF**: 嘗試在沒有 CSRF token 的情況下提交表單 (使用瀏覽器開發者工具移除)。驗證是否被拒絕。
