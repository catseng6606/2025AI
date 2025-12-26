# 安全程式範例：身分驗證與請求安全 (ASP.NET Core, Node.js & Python)

本文件延伸自 `01_safe_search_combined.md`，針對 **CSRF (跨站請求偽造)** 與 **失效的身分驗證 (Broken Authentication)** 兩個議題，展示在不同框架中的防禦實作。

---

## 1. ASP.NET Core (Razor Pages)

### CSRF 防禦

ASP.NET Core 內建強大的 CSRF 防護機制 (Antiforgery)。

- **原理**：框架會自動產生 Token 並寫入 Cookie，同時在 Form 中插入隱藏欄位。
- **實作**：
  - 在 Razor Pages (`.cshtml`) 中使用 `<form method="post">` 時，預設會自動產生並驗證 Token。
  - 若需手動加入，可使用 `@Html.AntiForgeryToken()`。
  - 後端可加上 `[ValidateAntiForgeryToken]` 屬性 (Razor Pages 預設已啟用)。

```html
<!-- Login.cshtml -->
<form method="post">
  <!-- ASP.NET Core 會自動在此插入 <input type="hidden" name="__RequestVerificationToken" ...> -->
  <input type="text" name="username" />
  <input type="password" name="password" />
  <button type="submit">登入</button>
</form>
```

### 身分驗證與 Session Fixation 防禦

ASP.NET Core 的 Identity 或 Cookie Authentication 系統會自動處理 Session Fixation。

- **防禦方式**：當使用者登入 (`HttpContext.SignInAsync`) 時，系統會發出全新的加密 Cookie，舊的匿名 Cookie 即便存在也無法用於存取授權資源。
- **最佳實踐**：使用標準的 `Microsoft.AspNetCore.Authentication.Cookies`。

```csharp
// AccountController.cs
[HttpPost]
public async Task<IActionResult> Login(string username, string password)
{
    if (IsValidUser(username, password))
    {
        var claims = new List<Claim> { new Claim(ClaimTypes.Name, username) };
        var identity = new ClaimsIdentity(claims, "CookieAuth");
        var principal = new ClaimsPrincipal(identity);

        // SignInAsync 會重新產生 Auth Cookie，有效防止 Session Fixation
        await HttpContext.SignInAsync("CookieAuth", principal);
        return RedirectToPage("/Index");
    }
    return Page();
}
```

---

## 2. Node.js (Express)

### CSRF 防禦

Express 需要依賴 Middleware 來實作，常見的有 `csurf` (已停止維護) 或較新的 `csrf-csrf` / `csurf` 的替代品。

- **原理**：Middleware 產生 Token，存於 Session 或 Cookie，並要求前端在請求時帶上。

```javascript
// app.js
const csrf = require("csrf-csrf"); // 假設使用 csrf-csrf 套件
const cookieParser = require("cookie-parser");

app.use(cookieParser());
const { doubleCsrfProtection } = csrf({
  getSecret: () => "SecretKey", // 實務上應從環境變數讀取
});

// 在路由中使用保護
app.get("/form", doubleCsrfProtection, (req, res) => {
  // 將 Token 傳給前端 View
  res.render("form", { csrfToken: req.csrfToken() });
});

app.post("/process", doubleCsrfProtection, (req, res) => {
  res.send("驗證通過");
});
```

```html
<!-- views/form.ejs -->
<form action="/process" method="POST">
  <input type="hidden" name="_csrf" value="<%= csrfToken %>" />
  <button type="submit">送出</button>
</form>
```

### 身分驗證與 Session Fixation 防禦

使用 `express-session` 時，必須在登入成功後手動更新 Session ID。

- **防禦方式**：呼叫 `req.session.regenerate()`。

```javascript
// login route
app.post("/login", (req, res) => {
  const { username, password } = req.body;

  if (checkLogin(username, password)) {
    // [關鍵] 登入前先更換 Session ID
    req.session.regenerate((err) => {
      if (err) next(err);

      // 在新 Session 中寫入使用者資訊
      req.session.user = username;
      res.send("登入成功，Session ID 已更新");
    });
  }
});
```

---

## 3. Python (Flask)

### CSRF 防禦

Flask 通常搭配 `Flask-WTF` 擴充套件來處理表單與 CSRF。

- **原理**：`Flask-WTF` 會在每個表單中自動生成並驗證 `csrf_token`。

```python
# app.py
from flask_wtf.csrf import CSRFProtect

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key'
csrf = CSRFProtect(app) # 全域啟用 CSRF 保護
```

```html
<!-- templates/login.html -->
<form method="post">
  <!-- 自動產生隱藏欄位 -->
  <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
  <input type="text" name="username" />
  <button type="submit">Submit</button>
</form>
```

### 身分驗證與 Session Fixation 防禦

Flask 使用 Signed Cookies (Client-side session)，但若使用 Server-side session (如 `Flask-Session`) 或單純為了安全性，登入時仍應清除舊 Session。

- **防禦方式**：使用 `session.clear()` 清除舊資料，或依賴 `Flask-Login` (它會處理 Session 安全)。

```python
from flask import session, redirect, url_for
from flask_login import login_user

@app.route('/login', methods=['POST'])
def login():
    if check_user(request.form['username'], request.form['password']):
        # [關鍵] 清除舊 Session 資料，防止 Session Fixation
        session.clear()

        # 如果使用 Flask-Login，login_user() 內部通常會處理 Session 安全
        login_user(user)

        # 若手動管理：
        # session['user_id'] = user.id

        return redirect(url_for('index'))
```

---

## 總結比較

| 安全議題             | PHP (原生)                        | ASP.NET Core                         | Node.js (Express)          | Python (Flask)           |
| :------------------- | :-------------------------------- | :----------------------------------- | :------------------------- | :----------------------- |
| **CSRF 防禦**        | 手動產生/驗證 Token (`$_SESSION`) | 內建 Antiforgery (自動驗證)          | Middleware (`csrf-csrf`)   | 套件 `Flask-WTF`         |
| **Session Fixation** | `session_regenerate_id(true)`     | `HttpContext.SignInAsync` (自動處理) | `req.session.regenerate()` | `session.clear()` + 重設 |
