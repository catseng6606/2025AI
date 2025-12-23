-- 1. 統計導演產量
SELECT director_id, COUNT(*) AS movie_count
FROM movies
GROUP BY director_id;

-- 2. 導演的平均吸金能力
SELECT director_id, AVG(revenue) AS avg_revenue
FROM movies
GROUP BY director_id
ORDER BY avg_revenue DESC;

-- 3. 預算最高紀錄
SELECT director_id, MAX(budget) AS max_budget
FROM movies
GROUP BY director_id;

-- 4. 評分總投票統計
SELECT director_id, SUM(vote_count) AS total_votes
FROM movies
GROUP BY director_id;

-- 5. 篩選資深導演
SELECT director_id, COUNT(*) AS movie_count
FROM movies
GROUP BY director_id
HAVING COUNT(*) > 15;

-- 6. 高評價門檻
SELECT director_id, AVG(vote_average) AS avg_vote, COUNT(*) AS movie_count
FROM movies
GROUP BY director_id
HAVING avg_vote > 7.5 AND movie_count > 3;

-- 7. 票房達標大師
SELECT director_id, SUM(revenue) AS total_revenue
FROM movies
GROUP BY director_id
HAVING total_revenue > 500000000;

-- 8. 熱門度與規模分析
SELECT director_id, AVG(popularity) AS avg_popularity, SUM(vote_count) AS total_votes
FROM movies
GROUP BY director_id
HAVING total_votes > 1000;

-- 9. 年度電影產量
SELECT SUBSTR(release_date, 1, 4) AS year, COUNT(*) AS movie_count
FROM movies
GROUP BY year;

-- 10. 投資報酬率過濾
SELECT director_id, SUM(budget) AS total_budget, SUM(revenue) AS total_revenue
FROM movies
GROUP BY director_id
HAVING total_revenue >= 2 * total_budget;
