-- sqlite
-- Create Index 練習
-- 用來建立索引以提升查詢性能。
-- 1. 為 users 表的 username 欄位建立索引
CREATE INDEX idx_username ON users(username);
-- 2. 為 orders 表的 order_date 欄位建立索引
CREATE INDEX idx_order_date ON orders(order_date);
-- 3. 為 orders 表的 price 欄位建立複合索引 (order_date, price)
CREATE INDEX idx_order_date_price ON orders(order_date, price);
-- 4. 為 users 表的 email 欄位建立唯一索引
CREATE UNIQUE INDEX idx_unique_email ON users(email);
-- 5. 為 orders 表的 customer_id 欄位建立索引
CREATE INDEX idx_customer_id ON orders(customer_id);
-- 6. 為 users 表的 age 欄位降冪建立索引
CREATE INDEX idx_age_desc ON users(age DESC);


CREATE INDEX idx_courses_coursename ON Courses(CourseName)