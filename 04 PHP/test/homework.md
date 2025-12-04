# 這是一個上完網站基礎技術與安全後的的作業 (不包含 mysql) 

## 互動

- 所有回應包含文件一慮使用 zh-tw 台灣正體中文
- 產生一個 plan.md 文件
- 產生一個 task.md 文件
- 經由確認無誤後執行

## 題目

包含一個一個使用者Profile頁面，一個使用者Profile修改頁面，一個使用者Profile呈現頁面，不使用資料庫，使用 session 來儲存使用者資料

1. 一個使用者Profile頁面，包含

- 使用者ID (使用 input) 只能輸入英數字
- 使用者名稱 (使用 input) 只能輸入英文字母、空白與UTF-8中文
- 使用者出生年 (使用 input) 只能輸入數字並檢測合理性(1900-2100)
- 使用者性別 (使用 select) 要有兩個選項生理男與生理女其他
- 使用者照片 (使用 input file)，需驗證圖片格式
- 使用者IG與FB (使用 input) 並驗證格式，使用正則表達式
- 使用者自我介紹 (使用 textarea)，可清洗
- CSRF token (使用 input hidden)
- 提交按鈕 (使用 button)，點選後將資料存在 Session 中，後轉址到使用者Profile呈現頁面

2. 一個使用者Profile呈現頁面，呈現時留意 XSS 與 CSRF 的基礎防範

- 使用者ID (使用 input) 只能輸入英數字
- 使用者名稱 (使用 input) 只能輸入英文字母、空白與UTF-8中文
- 使用者出生年 (使用 input) 只能輸入數字並檢測合理性(1900-2100)
- 使用者性別 (使用 select) 要有兩個選項生理男與生理女其他
- 使用者照片 (使用 input file)
- 使用者IG與FB (使用 input) 並驗證格式
- 使用者自我介紹 (使用 textarea)
- 提交按鈕 (使用 button)

## 技術堆疊包含 (留意版本)

- HTML5
- CSS3
- JavaScript
- PHP 8.2
- BOOTSTRAP 5.X
- XSS 與 CSRF 的基礎防範

## 安全確認

- 輸出時需要使用 htmlspecialchars using ENT_QUOTES，並留意防範偽協議與類似 onerror、onload、onfocus 等事件的 Xss 攻擊
- 輸入時需要使用驗證，提交時留意防範 CSRF 攻擊，使用 token
- 使用 CSP、HttpOnly、Secure、SameSite 與 Session 來防範 XSS 與 CSRF 攻擊
- 確保前後端驗證規則一致


## 版面布局

- 使用 bootstrap
- 使用 bootstrap 的 form layout
- 使用 單欄布局(12 columns)由上到下包含 header (1 column)、main (10 columns)、footer (1 column)
- header 包含 title (使用 h1)
- main 包含 form
- footer 包含 copyright (使用 p)
- 整體色系以粉色系為主，簡潔、大方

## 完成

1. Profile修改頁面: edit.php
2. Profile呈現頁面: profile.php
3. 環境變數設定: .env (設定 UPLOAD_DIR)

## 測試與驗證

- 提供建議或指令由我自行手動測試
