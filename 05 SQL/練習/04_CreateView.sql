-- Create View 練習
CREATE VIEW directors_man AS
select * from directors
where gender = 1;

-- Drop View 練習
DROP VIEW IF EXISTS directors_man;