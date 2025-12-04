# 測試與驗證建議

## 1. 表單驗證
- 測試所有欄位皆為空，應顯示必填錯誤訊息。
- 測試使用者ID輸入非英數字，應顯示錯誤。
- 測試使用者名稱輸入特殊符號，應顯示錯誤。
- 測試出生年輸入 1899、2101、非數字，應顯示錯誤。
- 測試性別未選擇，應顯示錯誤。
- 測試 IG/FB 欄位輸入不符格式，應顯示錯誤。
- 測試自我介紹輸入 XSS payload，顯示時應無惡意腳本。

## 2. 圖片上傳
- 上傳非圖片檔案，應顯示錯誤。
- 上傳 jpg/png/gif/webp 圖片，應成功顯示。
- 上傳大檔案測試（>2MB），應有伺服器端限制。

## 3. CSRF/XSS 防護
- 直接 POST 無效 token，應顯示 CSRF 錯誤。
- 編輯頁面與顯示頁面所有欄位皆以 htmlspecialchars(ENT_QUOTES) 輸出。
- 測試 IG/FB 欄位輸入 javascript: 或 onerror/onload/onfocus 等事件，應無法觸發。

## 4. Session 與導向
- 未登入直接進入 profile.php，應自動導向 edit.php。
- 編輯成功後自動導向 profile.php。

## 5. 版面與相容性
- 不同裝置（桌機/手機）顯示正常。
- 粉色系單欄 Bootstrap 5 版面。

## 6. 其他
- .env 設定 UPLOAD_DIR 有效。
- uploads 目錄權限正確。
