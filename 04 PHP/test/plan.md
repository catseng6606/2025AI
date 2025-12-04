# 網站基礎技術與安全作業計畫

## 目標
- 實作一個無資料庫、以 Session 儲存的使用者 Profile 系統，包含修改與呈現頁面，並具備基本 XSS/CSRF 防護。

## 需求拆解
1. Profile 修改頁面 (edit.php)
    - 表單欄位：
        - 使用者ID (英數字)
        - 使用者名稱 (英文字母、空白、UTF-8中文)
        - 出生年 (1900-2100)
        - 性別 (生理男/生理女/其他)
        - 照片 (圖片格式驗證)
        - IG/FB (正則驗證)
        - 自我介紹 (可清洗)
        - CSRF token (hidden)
    - 驗證：前後端一致，防止 XSS/CSRF
    - 上傳圖片存放於 UPLOAD_DIR
    - 成功後導向 profile.php

2. Profile 呈現頁面 (profile.php)
    - 顯示所有欄位內容
    - 輸出時使用 htmlspecialchars(ENT_QUOTES)
    - 防範偽協議、onerror/onload/onfocus 等 XSS
    - 提供返回/編輯按鈕

3. .env 設定
    - 設定 UPLOAD_DIR 供圖片上傳

4. 版面設計
    - Bootstrap 5 單欄粉色系
    - header: h1
    - main: form
    - footer: copyright

5. 安全性
    - CSP、HttpOnly、Secure、SameSite、Session
    - 前後端驗證一致
    - 圖片格式驗證
    - CSRF token 驗證

## 進度規劃
1. 撰寫 plan.md、task.md
2. 建立 .env 設定 UPLOAD_DIR
3. 開發 edit.php
4. 開發 profile.php
5. 測試與驗證