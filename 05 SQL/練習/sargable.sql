SELECT * FROM titles WHERE LEFT(primary_title, 3) = 'The';
-- 使用 LIKE 替代運算
SELECT * FROM titles WHERE primary_title LIKE 'The%';

SELECT * FROM titles WHERE runtime_minutes / 60 > 2;
-- 使用常數代替運算
SELECT * FROM titles WHERE runtime_minutes > 120;

-- 假設想找 2020 年以後，但寫法如下：
SELECT * FROM titles WHERE ABS(premiered - 2022) <= 2;

SELECT * FROM titles WHERE premiered >= 2020;

SELECT * FROM titles WHERE premiered BETWEEN 2020 AND 2022;

