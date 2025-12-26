# 安全程式範例：搜尋功能實作 (ASP.NET Core, Node.js & Python)

本文件結合了輸入/輸出防護原則與搜尋功能的實際範例，展示如何在 ASP.NET Core、Node.js 與 Python Flask 中實作安全的搜尋功能。

---

## 1. ASP.NET Core (Razor Pages + Dapper)

### 安全重點

1.  **輸入防護**：驗證輸入內容（雖然此例僅為字串，但可加入長度或格式限制）。
2.  **SQL Injection 防禦**：使用 Dapper 的參數化查詢 (`@name`)，絕不使用字串串接。
3.  **XSS 防禦**：Razor 引擎 (`@`) 預設會對輸出進行 HTML Encode。

### 範例程式碼

**Search.cshtml.cs (PageModel)**

```csharp
using Dapper;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using System.Collections.Generic;
using System.Data;
using System.Linq;

public class SearchModel : PageModel
{
    private readonly IDbConnection _db;

    // 1. 取得輸入：使用屬性綁定或直接從 Request 讀取
    [BindProperty(SupportsGet = true)]
    public string SearchQuery { get; set; }

    public List<Product> Results { get; set; } = new();

    public SearchModel(IDbConnection db)
    {
        _db = db;
    }

    public void OnGet()
    {
        // 簡單的輸入處理：如果為 null 則轉為空字串
        SearchQuery = SearchQuery ?? string.Empty;

        if (!string.IsNullOrEmpty(SearchQuery))
        {
            // 2. 防禦 SQL Injection：使用參數化查詢 (Parameterized Query)
            // 即使 SearchQuery 包含惡意 SQL 指令 (如 "' OR '1'='1")，也會被視為純文字參數
            string sql = "SELECT * FROM Products WHERE Name LIKE @name";
            var parameters = new { name = "%" + SearchQuery + "%" };

            Results = _db.Query<Product>(sql, parameters).ToList();
        }
    }
}

public class Product
{
    public int Id { get; set; }
    public string Name { get; set; }
}
```

**Search.cshtml (View)**

```html
@page @model SearchModel @{ ViewData["Title"] = "搜尋結果"; }

<h2>搜尋</h2>

<form method="get">
  <!-- 3. 防禦 XSS：Razor 屬性輸出也會自動編碼 -->
  <input type="text" name="SearchQuery" value="@Model.SearchQuery" />
  <button type="submit">搜尋</button>
</form>

@if (!string.IsNullOrEmpty(Model.SearchQuery)) {
<!-- 3. 防禦 XSS：Razor @ 語法預設會進行 HTML Encode -->
<!-- 如果輸入 <script>alert(1)</script>，會被轉義顯示為純文字，不會執行 -->
<p>您搜尋的關鍵字是: @Model.SearchQuery</p>

<ul>
  @foreach (var product in Model.Results) {
  <li>@product.Name</li>
  }
</ul>
}
```

---

## 2. Node.js (Express + EJS)

### 安全重點

1.  **輸入防護**：處理 `undefined` 或 `null` 的輸入情況。
2.  **SQL Injection 防禦**：使用 `mysql2` 套件的參數化查詢 (`?`)。
3.  **XSS 防禦**：EJS 樣板引擎 (`<%= %>`) 預設會對輸出進行 HTML Escape。

### 範例程式碼

**app.js (Controller/Route)**

```javascript
const express = require("express");
const mysql = require("mysql2");
const app = express();

app.set("view engine", "ejs");

// 建立資料庫連線
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "password",
  database: "mydb",
});

app.get("/search", (req, res) => {
  // 1. 取得輸入：確保 q 至少為空字串，避免 undefined 錯誤
  const q = req.query.q || "";

  if (q) {
    // 2. 防禦 SQL Injection：使用參數化查詢 (Parameterized Query)
    // 使用 ? 作為佔位符，並將參數放入陣列中傳入
    const sql = "SELECT * FROM products WHERE name LIKE ?";
    const searchTerm = `%${q}%`;

    db.query(sql, [searchTerm], (err, results) => {
      if (err) throw err;
      // 渲染畫面
      res.render("search", { q, results });
    });
  } else {
    // 沒有搜尋條件時，回傳空結果
    res.render("search", { q, results: [] });
  }
});

app.listen(3000, () => {
  console.log("Server is running on port 3000");
});
```

**views/search.ejs (View)**

```html
<!DOCTYPE html>
<html>
  <head>
    <title>搜尋結果</title>
  </head>
  <body>
    <h2>搜尋</h2>

    <form method="get" action="/search">
      <!-- 3. 防禦 XSS：EJS <%= %> 語法會自動 Escape -->
      <input type="text" name="q" value="<%= q %>" />
      <button type="submit">搜尋</button>
    </form>

    <% if (q) { %>
    <!-- 3. 防禦 XSS：即使輸入惡意 Script，也會被轉義 -->
    <p>您搜尋的關鍵字是: <%= q %></p>

    <ul>
      <% results.forEach(function(product) { %>
      <li><%= product.name %></li>
      <% }); %>
    </ul>
    <% } %>
  </body>
</html>
```

---

## 3. Python (Flask + Jinja2)

### 安全重點

1.  **輸入防護**：使用 `request.args.get('q', '')` 處理預設值，避免 NoneType 錯誤。
2.  **SQL Injection 防禦**：使用 DB-API 2.0 的參數化查詢 (`?` 或 `%s`，視資料庫驅動而定，這裡以 sqlite3 的 `?` 為例)。
3.  **XSS 防禦**：Jinja2 樣板引擎 (`{{ }}`) 預設開啟自動轉義 (Autoescaping)。

### 範例程式碼

**app.py (Controller/Route)**

```python
from flask import Flask, request, render_template
import sqlite3

app = Flask(__name__)

def get_db_connection():
    conn = sqlite3.connect('database.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/search')
def search():
    # 1. 取得輸入：如果沒有 q 參數，預設為空字串
    q = request.args.get('q', '')

    results = []
    if q:
        conn = get_db_connection()
        # 2. 防禦 SQL Injection：使用參數化查詢
        # sqlite3 使用 ? 作為佔位符，參數必須是 tuple (search_term,)
        sql = "SELECT * FROM products WHERE name LIKE ?"
        search_term = f"%{q}%"

        results = conn.execute(sql, (search_term,)).fetchall()
        conn.close()

    # 3. 防禦 XSS：Jinja2 預設會自動轉義變數
    return render_template('search.html', q=q, results=results)

if __name__ == '__main__':
    app.run(debug=True)
```

**templates/search.html (View)**

```html
<!DOCTYPE html>
<html>
  <head>
    <title>搜尋結果</title>
  </head>
  <body>
    <h2>搜尋</h2>

    <form method="get" action="/search">
      <!-- 3. 防禦 XSS：Jinja2 {{ }} 語法預設自動 Escape -->
      <input type="text" name="q" value="{{ q }}" />
      <button type="submit">搜尋</button>
    </form>

    {% if q %}
    <!-- 3. 防禦 XSS：即使輸入惡意 Script，也會被轉義顯示 -->
    <p>您搜尋的關鍵字是: {{ q }}</p>

    <ul>
      {% for product in results %}
      <li>{{ product['name'] }}</li>
      {% endfor %}
    </ul>
    {% endif %}
  </body>
</html>
```

---

## 總結比較

| 安全層面               | ASP.NET Core (Razor)                | Node.js (Express + EJS)         | Python (Flask + Jinja2)        |
| :--------------------- | :---------------------------------- | :------------------------------ | :----------------------------- |
| **輸入取得**           | `[BindProperty]` 或 `Request.Query` | `req.query`                     | `request.args.get()`           |
| **SQL Injection**      | Dapper `@param` 參數化查詢          | mysql2 `?` 參數化查詢           | DB-API `?` 或 `%s` 參數化查詢  |
| **XSS (HTML Context)** | `@Variable` (自動 Encode)           | `<%= variable %>` (自動 Escape) | `{{ variable }}` (自動 Escape) |
| **XSS (JS Context)**   | `Json.Serialize(Variable)`          | `JSON.stringify(variable)`      | `tojson` filter                |
