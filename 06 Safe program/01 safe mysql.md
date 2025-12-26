# MySQL 實作範例

MySQL 的機制略有不同，它是透過 SQL SECURITY DEFINER 來決定執行權限。當設定為 DEFINER 時，只要「建立 SP 的人」有權限，執行者就不需要資料表權限。

```sql
-- 【第一部：帳號隔離】
-- 建立一個僅限本地連線的低權限帳號
CREATE USER 'web_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';

-- 【第二部：封裝】
-- 使用管理員帳號建立資料表與 SP
-- 關鍵字：SQL SECURITY DEFINER (這是 MySQL 實現權限轉移的關鍵)
DELIMITER //
CREATE DEFINER = 'admin_user'@'localhost'
PROCEDURE AddOrder(IN p_prod_id INT, IN p_amount DECIMAL(10,2))
SQL SECURITY DEFINER
BEGIN
    INSERT INTO Orders (ProductID, Amount) VALUES (p_prod_id, p_amount);
END //
DELIMITER ;

-- 【第三部：執行與授權】
-- 僅授予執行權限
GRANT EXECUTE ON PROCEDURE your_db.AddOrder TO 'web_user'@'localhost';

-- 注意：web_user 此時若嘗試執行 SELECT * FROM Orders 會被拒絕
```
