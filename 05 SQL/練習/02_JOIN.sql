CREATE TABLE 課程表 (
    課程編號 TEXT PRIMARY KEY,
    課程名稱 TEXT NOT NULL,
    學分 INTEGER NOT NULL
);

CREATE TABLE 學生表 (
    學號 TEXT PRIMARY KEY,
    姓名 TEXT NOT NULL,
    課程編號 TEXT,
    FOREIGN KEY (課程編號) REFERENCES 課程表 (課程編號)
);

INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C001', '會計一', 1);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C002', '俄羅斯史', 2);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C003', '三民主義', 3);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C004', '事業經濟', 4);
INSERT INTO 課程表 (課程編號, 課程名稱, 學分) VALUES ('C005', '五權憲法', 5);

INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S001', '趙一', 'C001');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S002', '錢二', 'C002');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S003', '張三', 'C005');
INSERT INTO 學生表 (學號, 姓名, 課程編號) VALUES ('S004', '李四', NULL);


select * from  學生表 s
INNER join 課程表 c on s.課程編號 = c.課程編號


select * from  學生表 s
left join 課程表 c on s.課程編號 = c.課程編號

select * from  學生表 s
right join 課程表 c on s.課程編號 = c.課程編號