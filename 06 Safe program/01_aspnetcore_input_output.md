# ASP.NET Core (Razor Page + Dapper) 安全程式：資料的輸出與輸入

## 不安全的 C# 寫法

```csharp
// 假設這是搜尋功能
string searchQuery = Request.Query["q"];
ViewData["Message"] = $"您搜尋的關鍵字是: {searchQuery}";
var results = db.Query($"SELECT * FROM Products WHERE Name LIKE '%{searchQuery}%'");
// 請思考有哪些弱點
```
[ ] XSS
[ ] SQL injection
[ ] CSRF

## 安全的 C# 寫法

```csharp
// 1. 取得輸入
string searchQuery = Request.Query["q"] ?? string.Empty;

// 2. 防禦 XSS：Razor 預設自動 HTML encode
ViewData["Message"] = $"您搜尋的關鍵字是: {searchQuery}";

// 3. 防禦 SQL Injection：使用 Dapper 參數化查詢
var results = db.Query("SELECT * FROM Products WHERE Name LIKE @name", new { name = "%" + searchQuery + "%" });
```

[ ] 輸入
[ ] 輸出

## 輸入防護

1. 驗證 (Validation) — 「檢查你是不是我要的東西」
    - 類型檢查：int.TryParse()、DateTime.TryParse() 等。
    - 格式檢查：Regex 驗證 email、手機等格式。
    - 白名單 (Whitelisting)：只允許特定值，例如排序功能只允許 "ASC" 或 "DESC"。

2. 過濾與清洗 (Sanitization) — 「把你身上危險的東西拿掉」
    - 去除雜質：Regex.Replace() 去除 HTML 標籤。
    - .NET 工具：System.Net.WebUtility.HtmlEncode() 處理特殊字元。

## 輸出防護

1. 上下文轉義 (Contextual Escaping)
    - Razor 預設自動 HTML encode。
    - 放入 JavaScript：使用 Json.Serialize()。
    - 放入 URL：使用 UrlEncoder.Default.Encode()。

2. 設定安全標頭 (Security Headers)
    - Content Security Policy (CSP)：可於 Startup 設定 Response Headers。
