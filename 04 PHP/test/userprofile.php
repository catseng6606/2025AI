/*
TODO: 請完成一個使用者的 Profile [編輯]頁面 userinfo.php，內容如下：
1. 頁面標題為 "使用者 Profile"
2. 欄位:
    - User ID (user_id) 必填英數字
    - 使用者名稱 (name) 必填
    - 電子郵件 (email) 必填
    - 年齡 (age)
    - 個人經歷 
    - 圖片連結
    - FB 或 IG 連結
3. 先填寫表單，提交後 User ID 寫入 Cookie，其他欄位顯示在頁面上，其他放在 Session 中
4. 輸出時使用 htmlspecialchars 避免 XSS 攻擊
5. SameSite 設為 Lax
6. 使用 CSP 防止 XSS 攻擊
7. 使用 Bootstrap 美化頁面
8. 粉紅色系主題
9. href 設定需要驗證 https://{url.com} 開頭
10. 表單需有 CSRF 防護
11. 使用 html5 , header nav main footer 單欄式布局
*/