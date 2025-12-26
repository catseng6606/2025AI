-- 返回單一值：搭配比較運算子
-- 範例：找出預算（budget）高於「所有電影平均預算」的電影標題。
SELECT title, budget 
FROM movies 
WHERE budget > (SELECT AVG(budget) FROM movies);

-- 返回單一欄位列表：搭配 IN / ANY / ALL
-- 範例：找出所有「執導過票房超過 10 億美元電影」的導演姓名。
SELECT name 
FROM directors 
WHERE id IN (SELECT director_id FROM movies WHERE revenue > 1000000000);

-- 在 FROM 子句中使用子查詢
-- 範例：計算「每位導演平均執導幾部電影」。

SELECT AVG(movie_count) 
FROM (
    SELECT COUNT(id) AS movie_count 
    FROM movies 
    GROUP BY director_id
) AS temp_counts;

-- 在 SELECT 子句中使用子查詢
-- 範例：列出導演姓名，並在旁邊顯示他們各自執導的電影總數。
SELECT 
    name, 
    (SELECT COUNT(*) FROM movies WHERE director_id = directors.id) AS total_movies
FROM directors;

-- 在 HAVING 子句中使用子查詢
-- 範例：找出那些「平均電影評分」高於「全表總平均評分」的導演 ID。

SELECT director_id, AVG(vote_average)
FROM movies
GROUP BY director_id
HAVING AVG(vote_average) > (SELECT AVG(vote_average) FROM movies);

