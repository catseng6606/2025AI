# 作業
這份作業將使用 directors（7,110 筆）與 movies（48,399 筆）這兩張表。

## 第一部分：基礎與關聯查詢

1. 找出特定導演的電影： 請撰寫 SQL 找出導演姓名為 'Christopher Nolan' 的所有電影名稱（title）與發行日期（release_date）。

填空題：請補齊下列 SQL 的空格
```sql
SELECT ____ , ____ FROM movies WHERE ____ = (SELECT ____ FROM ____ WHERE ____ = 'Christopher Nolan');
```
或
```sql
SELECT m.____ , m.____ FROM movies m JOIN ____ d ON m.____ = d.____ WHERE d.____ = 'Christopher Nolan';
```

2. 跨表關聯練習： 請列出所有電影的標題（title）及其對應的導演姓名（name）。

填空題：
```sql
SELECT m.____ , d.____ FROM ____ m LEFT JOIN ____ d ON m.____ = d.____;
```

## 第二部分：分組與聚合統計 (GROUP BY & HAVING)

3. 計算每位導演的產量： 請統計每位導演執導的電影總數，輸出「導演姓名」與「電影數量」。

填空題：
```sql
SELECT d.____ , COUNT(m.____) AS movie_count FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____;
```

4. 篩選高產量導演： 承接上一題，請篩選出執導電影數量 超過 10 部 的導演，並依數量由多到少排序。

填空題：
```sql
SELECT d.____ , COUNT(m.____) AS movie_count FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____ HAVING COUNT(m.____) > ____ ORDER BY movie_count ____;
```

5. 票房大亨分析： 請計算每位導演所執導電影的 總票房收入（revenue），並找出總票房最高的前 5 名導演。

填空題：
```sql
SELECT d.____ , SUM(m.____) AS total_revenue FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____ ORDER BY total_revenue ____ LIMIT ____;
```

6. 部門平均評分： directors 表中有 department 欄位。請計算每個部門（Department）所執導電影的 平均評分（vote_average）。

填空題：
```sql
SELECT d.____ , AVG(m.____) AS avg_vote FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____;
```

## 第三部分：進階篩選與計算

7. 投資報酬率 (ROI) 分組： 請找出「總預算（budget）超過 1 億美元」的導演，並計算他們執導電影的平均評分。

填空題：
```sql
SELECT d.____ , AVG(m.____) AS avg_vote FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____ HAVING SUM(m.____) > ____;
```


8. 找出空窗期導演： 請利用 HAVING 找出所有執導電影總數「剛好等於 1 部」的導演姓名。

填空題：
```sql
SELECT d.____ FROM ____ d JOIN ____ m ON d.____ = m.____ GROUP BY d.____ HAVING COUNT(m.____) = ____;
```

## 第四部分：效能優化 (Explain Query Plan)

9. 讀取執行計畫： 執行以下 SQL 並觀察 EXPLAIN QUERY PLAN 的結果：

``` SQL
SELECT d.name, COUNT(m.id) 
FROM directors d 
JOIN movies m ON d.id = m.director_id 
GROUP BY d.id;
```


請說明：你在結果中看到了 SCAN 還是 SEARCH？這代表資料庫正在做什麼？

填空題：
____ 代表資料庫會全表掃描（Full Table Scan），____ 則代表有用到索引進行搜尋。
若看到 ____，表示查詢效能較低，建議建立索引。


10. 實戰優化題： 如果你發現第 9 題的結果出現了 USE TEMP B-TREE FOR GROUP BY，請寫出一個 CREATE INDEX 指令來優化這個查詢，並說明為什麼這個索引能幫助加速。

填空題：
```sql
CREATE INDEX ____ ON ____ (____);
```
這樣 GROUP BY d.id 時，JOIN 及分組都能利用 ____ 加速查詢。

``` sql
CREATE TABLE `directors` (
`name` TEXT,
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`gender` INTEGER,
`uid` INTEGER,
`department` TEXT)

CREATE TABLE `movies` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`original_title` VARCHAR,
`budget` INTEGER,
`popularity` INTEGER,
`release_date` TEXT,
`revenue` INTEGER,
`title` TEXT,
`vote_average` REAL,
`vote_count` INTEGER,
`overview` TEXT,
`tagline` TEXT, uid INTEGER, director_id INTEGER NOT NULL DEFAULT 0)
```