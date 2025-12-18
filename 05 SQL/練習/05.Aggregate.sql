-- Aggregate Functions 練習
-- 1. 計算 directors 表中有多少位導演
SELECT COUNT(*) AS director_count FROM directors;
-- 2. 計算 movies 表中的平均評分
SELECT AVG(rating) AS average_rating FROM movies;
-- 3. 計算 movies 表中的最高評分
SELECT MAX(rating) AS highest_rating FROM movies;
-- 4. 計算 movies 表中的最低評分
SELECT MIN(rating) AS lowest_rating FROM movies;
-- 5. 計算 movies 表中評分總和
SELECT SUM(rating) AS total_rating FROM movies;
-- 6. 計算每個導演的電影數量
SELECT director_id, COUNT(*) AS movie_count
FROM movies
GROUP BY director_id;
-- 7. 計算每個導演的平均電影評分
SELECT director_id, AVG(rating) AS average_rating
FROM movies
GROUP BY director_id;
-- 8. 計算每個導演的最高電影評分
SELECT director_id, MAX(rating) AS highest_rating   
FROM movies
GROUP BY director_id;
-- 9. 計算每個導演的最低電影評分
SELECT director_id, MIN(rating) AS lowest_rating
FROM movies
GROUP BY director_id;
-- 10. 計算每個導演的電影評分總和
SELECT director_id, SUM(rating) AS total_rating 
FROM movies
GROUP BY director_id;
-- 11. 計算每個評分等級的電影數量
SELECT rating, COUNT(*) AS movie_count
FROM movies
GROUP BY rating;
-- 12. 計算每個導演的電影數量，僅顯示電影數量超過 5 部的導演
SELECT director_id, COUNT(*) AS movie_count
FROM movies
GROUP BY director_id
HAVING movie_count > 5;