-- 返回單一值：搭配比較運算子

SELECT title, budget 
FROM movies 
WHERE budget > (SELECT AVG(budget) FROM movies);

-- 返回單一欄位列表：搭配 IN / ANY / ALL

SELECT name 
FROM directors 
WHERE id IN (SELECT director_id FROM movies WHERE revenue > 1000000000);

-- 在 FROM 子句中使用子查詢


SELECT AVG(movie_count) 
FROM (
    SELECT COUNT(id) AS movie_count 
    FROM movies 
    GROUP BY director_id
) AS temp_counts;

-- 在 SELECT 子句中使用子查詢

SELECT 
    name, 
    (SELECT COUNT(*) FROM movies WHERE director_id = directors.id) AS total_movies
FROM directors;

-- 在 HAVING 子句中使用子查詢

SELECT director_id, AVG(vote_average)
FROM movies
GROUP BY director_id
HAVING AVG(vote_average) > (SELECT AVG(vote_average) FROM movies);

