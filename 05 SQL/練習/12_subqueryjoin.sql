-- 改寫為 JOIN 版本並加上適當索引

-- 1. 返回單一值：JOIN 寫法
-- 建議加在 budget 欄位的索引
CREATE INDEX idx_movies_budget ON movies(budget);

SELECT m.title, m.budget
FROM movies m
JOIN (SELECT AVG(budget) AS avg_budget FROM movies) avgm ON m.budget > avgm.avg_budget;

-- 2. IN 子查詢改 JOIN
-- 建議加在 movies.director_id 與 movies.revenue 的索引
CREATE INDEX idx_movies_director_id ON movies(director_id);
CREATE INDEX idx_movies_revenue ON movies(revenue);

SELECT d.name
FROM directors d
JOIN movies m ON d.id = m.director_id
WHERE m.revenue > 1000000000
GROUP BY d.id, d.name;

-- 3. FROM 子查詢改 JOIN
-- 這題本質是 group by + avg
SELECT AVG(movie_count) AS avg_movie_count
FROM (
    SELECT director_id, COUNT(id) AS movie_count
    FROM movies
    GROUP BY director_id
) t;
-- 這題已經是最佳寫法，無法用 JOIN 取代 FROM 子查詢

-- 4. SELECT 子查詢改 JOIN + GROUP BY
SELECT d.name, COUNT(m.id) AS total_movies
FROM directors d
LEFT JOIN movies m ON d.id = m.director_id
GROUP BY d.id, d.name;

-- 5. HAVING 子查詢改 JOIN
SELECT m.director_id, AVG(m.vote_average) AS avg_vote
FROM movies m
GROUP BY m.director_id
HAVING AVG(m.vote_average) > (
    SELECT AVG(vote_average) FROM movies
);
-- HAVING 子查詢這種全表平均，JOIN 寫法無法完全取代，但可加 vote_average 索引
CREATE INDEX idx_movies_vote_average ON movies(vote_average);
