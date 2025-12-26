# Python 安全程式：資料的輸出與輸入

## 不安全的 Python 寫法

```python
# 假設這是搜尋功能
search_query = request.args.get('q')
# Jinja2 範例：將資料傳給模板
from flask import render_template
# ...
# return render_template('search.html', search_query=search_query)
results = db.execute(f"SELECT * FROM products WHERE name LIKE '%{search_query}%' ")
# 請思考有哪些弱點
```
[ ] XSS
[ ] SQL injection
[ ] CSRF

## 安全的 Python 寫法（以 Flask + sqlite3 為例）

```python
from flask import request, escape
import sqlite3

# 1. 取得輸入
search_query = request.args.get('q', '')

# 2. 防禦 XSS：Jinja2 預設自動 HTML escape，直接傳給模板即可
# return render_template('search.html', search_query=search_query)

# 3. 防禦 SQL Injection：使用參數化查詢
conn = sqlite3.connect('example.db')
cursor = conn.cursor()
search_term = f"%{search_query}%"
cursor.execute("SELECT * FROM products WHERE name LIKE ?", (search_term,))
results = cursor.fetchall()
```

[ ] 輸入
[ ] 輸出

## 輸入防護

1. 驗證 (Validation) — 「檢查你是不是我要的東西」

- 類型檢查：如果預期是數字（如 ID），可用 int() 強制轉型。
- 格式檢查：可用 re.match() 驗證 email、手機等格式。
- 白名單 (Whitelisting)：只允許特定值，例如排序功能只允許 'ASC' 或 'DESC'。

2. 過濾與清洗 (Sanitization) — 「把你身上危險的東西拿掉」

- 去除雜質：可用 re.sub() 去除 HTML 標籤。
- Python 工具：可用 html.escape() 處理特殊字元。

## 輸出防護

1. 上下文轉義 (Contextual Escaping)
根據資料要放哪裡，決定怎麼包裝。

- 放入 HTML 內容：使用 html.escape() 或 Flask 的 escape()。
    - 原本：<script> → 包裝後：&lt;script&gt;（瀏覽器只會顯示字，不會執行腳本）。
- 放入 JavaScript 變數：使用 json.dumps()。
- 放入 URL：使用 urllib.parse.quote()。

2. 設定安全標頭 (Security Headers)
- Content Security Policy (CSP)：可用 Flask 的 after_request 設定 response headers，限制腳本來源，防禦 XSS。
