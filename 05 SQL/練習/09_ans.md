# 08_homework 作業參考答案

## 1. 找出特定導演的電影
```sql
SELECT title, release_date FROM movies WHERE director_id = (SELECT id FROM directors WHERE name = 'Christopher Nolan');
-- 或
SELECT m.title, m.release_date FROM movies m JOIN directors d ON m.director_id = d.id WHERE d.name = 'Christopher Nolan';
```

## 2. 跨表關聯練習
```sql
SELECT m.title, d.name FROM movies m LEFT JOIN directors d ON m.director_id = d.id;
```

## 3. 計算每位導演的產量
```sql
SELECT d.name, COUNT(m.id) AS movie_count FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.id;
```

## 4. 篩選高產量導演
```sql
SELECT d.name, COUNT(m.id) AS movie_count FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.id HAVING COUNT(m.id) > 10 ORDER BY movie_count DESC;
```

## 5. 票房大亨分析
```sql
SELECT d.name, SUM(m.revenue) AS total_revenue FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.id ORDER BY total_revenue DESC LIMIT 5;
```

## 6. 部門平均評分
```sql
SELECT d.department, AVG(m.vote_average) AS avg_vote FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.department;
```

## 7. 投資報酬率 (ROI) 分組
```sql
SELECT d.name, AVG(m.vote_average) AS avg_vote FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.id HAVING SUM(m.budget) > 100000000;
```

## 8. 找出空窗期導演
```sql
SELECT d.name FROM directors d JOIN movies m ON d.id = m.director_id GROUP BY d.id HAVING COUNT(m.id) = 1;
```

## 9. 讀取執行計畫
- SCAN 代表資料庫會全表掃描（Full Table Scan），SEARCH 則代表有用到索引進行搜尋。
- 若看到 SCAN，表示查詢效能較低，建議建立索引。

## 10. 實戰優化題
```sql
CREATE INDEX idx_movies_director_id ON movies(director_id);
```
這樣 GROUP BY d.id 時，JOIN 及分組都能利用索引加速查詢。
