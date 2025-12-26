SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";

CREATE TABLE users (
  id int(11) NOT NULL auto_increment,
  username varchar(64) NOT NULL,
  password varchar(64) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (id, username, password) VALUES (1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997'); -- 密碼: admin
INSERT INTO users (id, username, password) VALUES (2, 'user', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'); -- 密碼: password
INSERT INTO users (id, username, password) VALUES (3, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'); -- 密碼: test
INSERT INTO users (id, username, password) VALUES (4, 'guest', '35675e68f4b5af7b995d9205ad0fc43842f16450'); -- 密碼: guest
INSERT INTO users (id, username, password) VALUES (5, 'demo', '89e495e7941cf9e40e6980d14a16bf023ccd4c91'); -- 密碼: demo

-- 課程表
CREATE TABLE 課程表 (
    課程編號 VARCHAR(10) PRIMARY KEY,
    課程名稱 VARCHAR(50) NOT NULL,
    學分 INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 學生表
CREATE TABLE 學生表 (
    學號 VARCHAR(10) PRIMARY KEY,
    姓名 VARCHAR(50) NOT NULL,
    課程編號 VARCHAR(10),
    FOREIGN KEY (課程編號) REFERENCES 課程表 (課程編號)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C001', '會計一', 1);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C002', '俄羅斯史', 2);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C003', '三民主義', 3);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C004', '事業經濟', 4);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C005', '五權憲法', 5);

INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S001', '趙一', 'C001');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S002', '錢二', 'C002');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S003', '張三', 'C005');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S004', '李四', NULL);

-- 查詢範例
-- select * from  學生表 s INNER join 課程表 c on s.課程編號 = c.課程編號;
-- select * from  學生表 s left join 課程表 c on s.課程編號 = c.課程編號;
-- select * from  學生表 s right join 課程表 c on s.課程編號 = c.課程編號;

