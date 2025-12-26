# 安全程式範例：環境與元件安全 (ASP.NET Core, Node.js & Python)

本文件延伸自 `01_safe_search_combined.md`，針對 **元件與依賴管理** 及 **敏感資料外洩** 兩個議題，展示在不同框架中的防禦實作。

---

## 1. ASP.NET Core

### 元件與依賴管理 (NuGet)

- **風險**：使用含有已知漏洞的 NuGet 套件。
- **防禦**：
  - **Visual Studio**：NuGet 套件管理員會自動標示有漏洞的套件。
  - **CLI**：使用 `dotnet list package --vulnerable` 指令檢查。

```bash
# 檢查專案中是否有已知漏洞的套件
dotnet list package --vulnerable
```

### 敏感資料與環境設定

- **機密管理**：
  - **開發環境**：使用 **Secret Manager (User Secrets)**，避免機密誤傳至 Git。
  - **生產環境**：使用環境變數或 Azure Key Vault。
  - **設定檔**：`appsettings.json` 不應包含密碼。
- **錯誤處理**：
  - 在 `Startup.cs` 或 `Program.cs` 中區分環境。生產環境應使用 `ExceptionHandler`，避免黃頁 (Yellow Screen of Death) 洩漏堆疊追蹤 (Stack Trace)。

```csharp
// Program.cs
if (!app.Environment.IsDevelopment())
{
    // 生產環境：導向通用錯誤頁面，不顯示詳細錯誤資訊
    app.UseExceptionHandler("/Error");
    // 啟用 HSTS (HTTP Strict Transport Security)
    app.UseHsts();
}
else
{
    // 開發環境：顯示詳細錯誤頁面以利除錯
    app.UseDeveloperExceptionPage();
}
```

---

## 2. Node.js (Express)

### 元件與依賴管理 (npm)

- **風險**：npm 生態系龐大，依賴鏈複雜，容易引入惡意或有漏洞的套件。
- **防禦**：
  - **npm audit**：安裝或更新時自動檢查，也可手動執行。
  - **npm audit fix**：嘗試自動修復漏洞版本。

```bash
# 檢查漏洞
npm audit

# 嘗試自動修復
npm audit fix
```

### 敏感資料與環境設定

- **機密管理**：
  - 使用 `dotenv` 套件讀取 `.env` 檔案。
  - 確保 `.env` 在 `.gitignore` 中。
- **錯誤處理**：
  - 設定 `NODE_ENV=production`。
  - 實作全域錯誤處理 Middleware，避免將 `err.stack` 回傳給客戶端。

```javascript
// app.js
require("dotenv").config(); // 載入 .env

// 讀取機密
const dbPassword = process.env.DB_PASS;

// 全域錯誤處理
app.use((err, req, res, next) => {
  console.error(err.stack); // 記錄到伺服器 Log

  // 回傳給使用者的訊息
  res.status(500).json({
    error:
      process.env.NODE_ENV === "production"
        ? "Internal Server Error"
        : err.message, // 開發環境才顯示詳細錯誤
  });
});
```

---

## 3. Python (Flask)

### 元件與依賴管理 (pip)

- **風險**：PyPI 套件可能含有漏洞。
- **防禦**：
  - 使用 `pip-audit` 或 `safety` 工具來掃描 `requirements.txt` 或目前環境。

```bash
# 安裝稽核工具
pip install pip-audit

# 掃描目前環境
pip-audit
```

### 敏感資料與環境設定

- **機密管理**：
  - 使用 `python-dotenv` 載入 `.env` 檔案。
  - 使用 `os.getenv()` 讀取變數。
- **錯誤處理**：
  - 生產環境絕對要關閉 Debug 模式 (`FLASK_DEBUG=0` 或 `app.run(debug=False)`).
  - 使用 `@app.errorhandler` 自訂錯誤頁面。

```python
# app.py
import os
from flask import Flask, render_template
from dotenv import load_dotenv

load_dotenv() # 載入 .env

app = Flask(__name__)
app.secret_key = os.getenv('SECRET_KEY') # 從環境變數讀取

# 自訂 500 錯誤頁面
@app.errorhandler(500)
def internal_error(error):
    # 不回傳 error 內容給使用者
    return render_template('500.html'), 500

if __name__ == '__main__':
    # 確保生產環境不開啟 debug
    debug_mode = os.getenv('FLASK_DEBUG', 'False').lower() in ['true', '1', 't']
    app.run(debug=debug_mode)
```

---

## 總結比較

| 安全議題             | PHP (Composer)         | ASP.NET Core (NuGet)                | Node.js (npm)         | Python (pip)            |
| :------------------- | :--------------------- | :---------------------------------- | :-------------------- | :---------------------- |
| **漏洞掃描指令**     | `composer audit`       | `dotnet list package --vulnerable`  | `npm audit`           | `pip-audit` 或 `safety` |
| **機密管理 (.env)**  | `vlucas/phpdotenv`     | `IConfiguration` (User Secrets/Env) | `dotenv`              | `python-dotenv`         |
| **生產環境錯誤隱藏** | `display_errors = Off` | `UseExceptionHandler("/Error")`     | `NODE_ENV=production` | `debug=False`           |
