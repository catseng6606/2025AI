select 
(select count(*) from titles) titles1_count ,
(select count(*) from titles2) titles2_count
EXPLAIN QUERY PLAN
select * from titles2
where original_title like 'Home A%'
EXPLAIN QUERY PLAN
select * from titles
where original_title like '%Home A%'

-- 1. 先刪除舊的索引
DROP INDEX ix_titles_original_title;

-- 2. 重新建立索引，加上 COLLATE NOCASE
CREATE INDEX ix_titles_original_title 
ON titles (original_title COLLATE NOCASE);