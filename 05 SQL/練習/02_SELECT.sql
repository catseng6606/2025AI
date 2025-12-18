-- SELECT 練習
-- 1. 從 users 表中選取所有欄位的所有資料
SELECT * FROM users;
-- 2. 從 users 表中選取 name 和 age 欄位的所有資料
SELECT name, age FROM users;
-- 3. 從 users 表中選取年齡大於 20 歲的使用者資料
SELECT * FROM users WHERE age > 20;
-- 4. 從 users 表中選取年齡在 18 到 30 歲之間的使用者資料
SELECT * FROM users WHERE age BETWEEN 18 AND 30;    
-- 5. 從 users 表中選取名稱以 'A' 開頭的使用者資料
SELECT * FROM users WHERE name LIKE 'A%';
-- 6. 從 orders 表中選取所有欄位的所有資料，並依照 order_date 降冪排序
SELECT * FROM orders ORDER BY order_date DESC;
-- 7. 從 orders 表中選取價格大於 100 的訂單資料，並依照 price 升冪排序
SELECT * FROM orders WHERE price > 100 ORDER BY price ASC;
-- 8. 從 users 表中選取不同年齡的使用者資料
SELECT DISTINCT age FROM users;