# 任務分解

## 1. 設定環境
- [ ] 建立 .env 並設定 UPLOAD_DIR

## 2. Profile 修改頁面 (edit.php)
- [ ] 建立表單 (Bootstrap 5)
- [ ] 前端驗證 (JS)
- [ ] 後端驗證 (PHP)
- [ ] 圖片上傳與驗證
- [ ] CSRF token 產生與驗證
- [ ] 資料存入 Session
- [ ] 成功導向 profile.php

## 3. Profile 呈現頁面 (profile.php)
- [ ] 讀取 Session 資料
- [ ] 輸出時 htmlspecialchars(ENT_QUOTES)
- [ ] 防範偽協議、onerror/onload/onfocus 等 XSS
- [ ] 呈現所有欄位
- [ ] 返回/編輯按鈕

## 4. 版面設計
- [ ] 粉色系單欄 Bootstrap 5
- [ ] header/main/footer

## 5. 安全性
- [ ] 設定 CSP
- [ ] 設定 HttpOnly、Secure、SameSite cookie
- [ ] 前後端驗證一致
- [ ] 圖片格式驗證
- [ ] CSRF token 驗證

## 6. 測試與驗證
- [ ] 手動測試所有功能
- [ ] 驗證安全性