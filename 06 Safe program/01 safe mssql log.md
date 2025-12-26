# MSSQL Log 實作範例

## 1. 日誌資料表與寫入程序

首先建立用於儲存日誌的資料表 `Sys_Log` 以及寫入日誌的預存程序 `usp_Sys_Log`。

```sql
-- 建立日誌資料表
CREATE TABLE dbo.Sys_Log (
    LogID INT IDENTITY(1,1) PRIMARY KEY,
    LogType NVARCHAR(20),       -- 紀錄類型 (INFO, ERROR, WARNING...)
    SpName NVARCHAR(100),       -- 執行的程序名稱（定義「誰」在做「什麼」）
    ParJson NVARCHAR(MAX),      -- Parameters：輸入參數。記錄發起請求的「初始意圖」
    BefJson NVARCHAR(MAX),      -- Before：執行前的資料狀態。「災難恢復」與「責任釐清」的關鍵快照
    AftJson NVARCHAR(MAX),      -- After：執行後的資料狀態。確認預期結果與實際產出是否一致
    ErrJson NVARCHAR(MAX),      -- Error：異常發生時的完整環境資訊
    Dcrt DATETIME DEFAULT GETDATE() -- Date Create：精確的時間戳記
);
GO

-- 建立寫入日誌的 SP
CREATE PROCEDURE dbo.usp_Sys_Log
    @LogType NVARCHAR(20),
    @SpName NVARCHAR(100),
    @BefJson NVARCHAR(MAX) = NULL,
    @AftJson NVARCHAR(MAX) = NULL,
    @ParJson NVARCHAR(MAX) = NULL,
    @ErrJson NVARCHAR(MAX) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO dbo.Sys_Log (LogType, SpName, BefJson, AftJson, ParJson, ErrJson)
    VALUES (@LogType, @SpName, @BefJson, @AftJson, @ParJson, @ErrJson);
END;
GO
```

## 2. 業務邏輯整合範例

在實際的業務 SP 中，利用 `usp_Sys_Log` 記錄執行前後的狀態與異常。

```sql
CREATE PROCEDURE dbo.usp_UpdateStock
    @ProdID INT, @NewQty INT
AS
BEGIN
    DECLARE @befJson NVARCHAR(MAX), @aftJson NVARCHAR(MAX), @parJson NVARCHAR(MAX);
    -- 序列化輸入參數
    SET @parJson = (SELECT @ProdID as ProdID, @NewQty as NewQty FOR JSON PATH, WITHOUT_ARRAY_WRAPPER);

    BEGIN TRY
        -- 1. 擷取變更前狀態 (Before)
        SET @befJson = (SELECT * FROM Products WHERE ID = @ProdID FOR JSON PATH, WITHOUT_ARRAY_WRAPPER);

        -- 2. 執行業務邏輯
        UPDATE Products SET Qty = @NewQty WHERE ID = @ProdID;

        -- 3. 擷取變更後狀態 (After)
        SET @aftJson = (SELECT * FROM Products WHERE ID = @ProdID FOR JSON PATH, WITHOUT_ARRAY_WRAPPER);

        -- 4. 寫入總日誌 (成功案例)
        EXEC dbo.usp_Sys_Log 'INFO', 'usp_UpdateStock', @befJson, @aftJson, @parJson, NULL;

    END TRY
    BEGIN CATCH
        -- 錯誤處理，紀錄 errJson
        DECLARE @err NVARCHAR(MAX) = (SELECT ERROR_NUMBER() as code, ERROR_MESSAGE() as msg FOR JSON PATH);
        -- 寫入總日誌 (失敗案例)
        EXEC dbo.usp_Sys_Log 'ERROR', 'usp_UpdateStock', @befJson, NULL, @parJson, @err;
        THROW;
    END CATCH
END;
GO
```
