# PHP 安全程式：資料的輸出與輸入

## 不安全的 PHP 寫法

``` php
// 假設這是搜尋功能
$search_query = $_GET['q'];
echo "您搜尋的關鍵字是: " . $search_query;
$results = $db->query("SELECT * FROM products WHERE name LIKE '%$search_query%'");
// 請思考有哪些弱點
```
[] XSS
[] SQL injection
[] CSRF


## 安全的 PHP 寫法

``` php
// 1. 取得輸入
$search_query = $_GET['q'] ?? '';

// 2. 防禦 XSS：在輸出到 HTML 前先轉義
// 使用 htmlspecialchars 確保 <script> 變成純文字
$safe_html_output = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');
echo "您搜尋的關鍵字是: " . $safe_html_output;

// 3. 防禦 SQL Injection：使用 PDO 預處理指令
$stmt = $db->prepare("SELECT * FROM products WHERE name LIKE :name");
// 這裡我們把百分比符號與變數結合，當作參數傳入
$search_term = "%$search_query%";
$stmt->execute(['name' => $search_term]);
$results = $stmt->fetchAll();
```

[] 輸入
[] 輸出

## 輸入防護

1. 驗證 (Validation) — 「檢查你是不是我要的東西」

- 類型檢查： 如果預期是數字（如 ID），就強制轉型為 (int)。
- 格式檢查： 使用 filter_var() 驗證電子郵件格式，或用正規表示式 (preg_match) 檢查手機號碼。
- 白名單 (Whitelisting)： 這是最強大的防護。例如：排序功能只允許 ASC 或 DESC，其他輸入一律拒絕。

2. 過濾與清洗 (Sanitization) — 「把你身上危險的東西拿掉」

- 去除雜質： 使用 strip_tags() 去除 HTML 標籤。
- PHP 工具： filter_var($input, FILTER_SANITIZE_STRING) 可以幫你過濾掉一些潛在的危險字元。

## 輸出防護

1. 上下文轉義 (Contextual Escaping)
這是最重要的觀念：根據資料要放哪裡，決定怎麼包裝。

- 放入 HTML 內容： 使用 `htmlspecialchars()`。
    - 原本： `<script>` → 包裝後： `&lt;script&gt;`（瀏覽器只會顯示字，不會執行腳本）。
- 放入 JavaScript 變數： 使用 `json_encode()`。這能確保資料符合 JS 語法且不會跳脫出來變成攻擊程式碼。
- 放入 URL： 使用 `urlencode()`。

2. 設定安全標頭 (Security Headers)
Content Security Policy (CSP)： 告訴瀏覽器哪些來源的腳本是可以執行的，這是防禦 XSS 的最後一道防線。

