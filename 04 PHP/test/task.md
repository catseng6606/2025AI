# 使用者 Profile 系統開發任務

## 1. 需求分析
- 依據作業說明，確認所有欄位與驗證規則
- 確認安全需求（XSS/CSRF 防護、Session、CSP 等）

## 2. 環境設置
- 建立 .env 設定 UPLOAD_DIR
- 建立 uploads 目錄

## 3. Profile 修改頁面 (edit.php)
- 建立表單，欄位含驗證
- 前端驗證（JS）
- 後端驗證（PHP）
- 上傳圖片格式驗證
- 產生並驗證 CSRF token
- 資料存入 Session
- 上傳檔案儲存至 UPLOAD_DIR
- 成功後導向 profile.php

## 4. Profile 呈現頁面 (profile.php)
- 從 Session 讀取資料
- 使用 htmlspecialchars(ENT_QUOTES) 輸出
- 防範偽協議、onerror/onload/onfocus 等事件
- 呈現圖片與欄位

## 5. 安全設計
- 設定 CSP header
- 設定 Session cookie: HttpOnly, Secure, SameSite
- 前後端驗證一致

## 6. 版面設計
- 使用 Bootstrap 5 粉色系
- 單欄布局（header/main/footer）

## 7. 測試與驗證
- 測試各欄位驗證、XSS/CSRF 防護
- 測試圖片上傳與顯示
- 測試 session 與 token
- 提供測試建議
