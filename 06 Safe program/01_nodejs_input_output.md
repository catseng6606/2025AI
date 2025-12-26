# Node.js Express (EJS) 安全程式：資料的輸出與輸入

## 不安全的 Node.js 寫法

```js
// 假設這是搜尋功能
const searchQuery = req.query.q;
res.send(`您搜尋的關鍵字是: ${searchQuery}`);
db.query(`SELECT * FROM products WHERE name LIKE '%${searchQuery}%'`, (err, results) => {
  // ...
});
// 請思考有哪些弱點
```
[ ] XSS
[ ] SQL injection
[ ] CSRF

## 安全的 Node.js 寫法

```js
// 1. 取得輸入
const searchQuery = req.query.q || '';

// 2. 防禦 XSS：EJS 預設 <%= %> 會自動 HTML escape
res.render('search', { searchQuery });

// 3. 防禦 SQL Injection：使用參數化查詢
const searchTerm = `%${searchQuery}%`;
db.query('SELECT * FROM products WHERE name LIKE ?', [searchTerm], (err, results) => {
  res.render('search', { searchQuery, results });
});
```

[ ] 輸入
[ ] 輸出

## 輸入防護

1. 驗證 (Validation) — 「檢查你是不是我要的東西」
    - 類型檢查：可用 parseInt()、isNaN() 等。
    - 格式檢查：可用正則表達式驗證 email、手機等格式。
    - 白名單 (Whitelisting)：只允許特定值，例如排序功能只允許 'ASC' 或 'DESC'。

2. 過濾與清洗 (Sanitization) — 「把你身上危險的東西拿掉」
    - 去除雜質：可用自訂函式或第三方套件（如 validator.js）去除 HTML 標籤。
    - Node.js 工具：escape-html 套件可處理特殊字元。

## 輸出防護

1. 上下文轉義 (Contextual Escaping)
    - EJS <%= %> 預設自動 HTML escape。
    - 放入 JavaScript：可用 JSON.stringify()。
    - 放入 URL：可用 encodeURIComponent()。

2. 設定安全標頭 (Security Headers)
    - Content Security Policy (CSP)：可用 helmet 套件設定 response headers。
