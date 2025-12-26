# MSSQL 實作範例

MSSQL 的機制略有不同，它是透過 SQL SECURITY DEFINER 來決定執行權限。當設定為 DEFINER 時，只要「建立 SP 的人」有權限，執行者就不需要資料表權限。

```sql
-- 【第一部：帳號隔離】
-- 建立登入帳號與資料庫使用者，此時 web_user 什麼權限都沒有
CREATE LOGIN web_app_login WITH PASSWORD = 'StrongPassword123!';
CREATE USER web_user FOR LOGIN web_app_login;

-- 【第二部：封裝】
-- 建立資料表 (通常由 dbo 建立)
CREATE TABLE dbo.Orders (
    ID INT PRIMARY KEY IDENTITY,
    ProductID INT,
    Amount DECIMAL(18,2)
);
GO

-- 建立 SP：將新增邏輯封裝起來
CREATE PROCEDURE dbo.usp_AddOrder
    @ProductID INT,
    @Amount DECIMAL(18,2)
AS
BEGIN
    INSERT INTO dbo.Orders (ProductID, Amount) VALUES (@ProductID, @Amount);
END;
GO

-- 【第三部：執行與授權】
-- 僅授予執行權，web_user 依然無法直接 SELECT * FROM Orders
GRANT EXECUTE ON dbo.usp_AddOrder TO web_user;
```
