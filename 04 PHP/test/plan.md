# 使用者 Profile 系統開發計畫

## 目標
建立一個無資料庫、以 Session 儲存資料的使用者 Profile 系統，包含 Profile 修改與呈現頁面，並具備基本 XSS/CSRF 防護。

## 需求摘要
- Profile 修改頁面 (edit.php)
- Profile 呈現頁面 (profile.php)
- .env 設定 UPLOAD_DIR
- Session 儲存使用者資料
- CSRF token 防護
- 前後端一致驗證
- XSS/CSRF 防護 (CSP, HttpOnly, Secure, SameSite)
- Bootstrap 5 粉色系單欄布局

## 頁面設計
### edit.php
- 使用者ID (input, 英數字)
- 使用者名稱 (input, 英文/空白/中文)
- 出生年 (input, 1900-2100)
- 性別 (select, 男/女/其他)
- 照片 (input file, 圖片格式驗證)
- IG/FB (input, 正則驗證)
- 自我介紹 (textarea, 清洗)
- CSRF token (hidden)
- 提交按鈕

### profile.php
- 呈現所有欄位，防範 XSS/CSRF

## 安全設計
- htmlspecialchars(ENT_QUOTES) 輸出
- CSP, HttpOnly, Secure, SameSite, Session
- 前後端驗證一致
- 防偽協議、onerror/onload/onfocus 等事件

## 版面設計
- Bootstrap 5 單欄粉色系
- header: h1
- main: form
- footer: p

## 檔案結構
- edit.php
- profile.php
- .env
- /uploads (上傳目錄)

## 測試
- 手動測試流程與建議
