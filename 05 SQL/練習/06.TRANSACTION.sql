-- 建立 accounts 資料表與測試資料 (for SQLite)
DROP TABLE IF EXISTS accounts;
CREATE TABLE accounts (
    account_id INTEGER PRIMARY KEY,
    name TEXT,
    balance INTEGER
);
INSERT INTO accounts (account_id, name, balance) VALUES (1, 'Alice', 1000);
INSERT INTO accounts (account_id, name, balance) VALUES (2, 'Bob', 1000);

-- Demo Transaction 練習
BEGIN TRANSACTION;
UPDATE accounts SET balance = balance - 500 WHERE account_id = 1;
UPDATE accounts SET balance = balance + 500 WHERE account_id = 2;
COMMIT;
-- 若要測試 ROLLBACK，可以使用以下語句
-- ROLLBACK;    
-- 若要測試錯誤回滾，可以使用以下語句
BEGIN TRANSACTION;
UPDATE accounts SET balance = balance - 500 WHERE account_id = 1;
-- 故意引入錯誤：將 account_id 設為不存在的值
UPDATE accounts SET balance = balance + 500 WHERE account_id = -9999;
ROLLBACK;   

