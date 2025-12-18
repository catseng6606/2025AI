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
-- 正常情況下，以上兩個 UPDATE 都應該成功執行
-- 加入 TRY CATCH 模擬錯誤處理
BEGIN TRANSACTION;
BEGIN TRY
    UPDATE accounts SET balance = balance - 500 WHERE account_id = 1;
    -- 故意引入錯誤：將 account_id 設為不存在的值
    UPDATE accounts SET balance = balance + 500 WHERE account_id = 9999;
    COMMIT;
END TRY
BEGIN CATCH
    ROLLBACK;
END CATCH;