# 作業需求文件分析與建議

針對 `homework.md` 的分析如下，旨在提升規格的明確性與符合現代安全實踐：

## 1. 版面布局描述 (Layout)
- **原文**: "使用 單欄布局(12 columns)由上到下包含 header (1 column)、main (10 columns)、footer (1 column)"
- **問題**: 在 Bootstrap Grid 系統中，"column" 通常指**寬度** (Width)。若 Header 僅佔 1 column (1/12 寬度)，會非常窄且不合常理。這裡似乎是想表達「垂直佔比」或是「Main 區塊的寬度」。
- **建議修改**:
    - 若是指 Main 區塊寬度：*"由上到下包含 Header (Full width)、Main (佔 10 columns 並置中)、Footer (Full width)"*。
    - 若是指垂直區塊：*"頁面分為 Header、Main、Footer 三大區塊"*。

## 2. 輸入驗證 (Validation)
- **原文**: "使用者名稱...只能輸入英文字母、空白與UTF-8中文"
- **問題**: "UTF-8中文" 定義較廣。
- **建議**: 建議明確規範是否包含「中文標點符號」或僅限「漢字」。若僅限漢字與英文，建議提供參考 Regex (如 `/^[\p{L}\s]+$/u`) 以統一標準。

## 3. 資料清洗 (Sanitization)
- **原文**: "使用者自我介紹...可清洗"
- **問題**: "可清洗" (Cleanable) 定義模糊。是 "移除所有標籤" (`strip_tags`) 還是 "允許部分標籤" (如 HTMLPurifier)?
- **建議**: 鑑於這是一門基礎課程且後續有要求 `htmlspecialchars`，建議明確要求 *"輸入時移除所有 HTML 標籤 (strip_tags)"*，以簡化實作並確保安全。

## 4. CSP 與安全性
- **原文**: "使用 CSP...來防範 XSS"
- **建議**: 由於 Bootstrap 某些組件或自定義樣式可能涉及 Inline Style，嚴格的 CSP (`default-src 'self'`) 可能會導致樣式失效。
- **建議修改**: 建議在技術堆疊或安全要求中，提示 *"建議使用 Nonce 搭配 CSP 以允許合法的 Inline Styles/Scripts"*，這符合更進階且正確的安全實踐。

## 5. 環境變數 (.env)
- **原文**: ".env (設定 UPLOAD_DIR)"
- **建議**: 由於未強制使用 Composer，建議提示 *"可使用 PHP 原生 `parse_ini_file` 讀取設定"*，以免學生誤以為必須引入外部套件。

## 6. 檔案上傳
- **原文**: "需驗證圖片格式"
- **建議**: 建議明確要求 *"需檢查檔案 MIME-Type (如使用 `finfo_file`)，不可僅依賴副檔名"*，這是檔案上傳安全的重要一環。

---
**總結**:
`homework.md` 的安全要求相當完整，但在「版面 Grid 描述」上容易造成誤解，建議優先修正該點。
