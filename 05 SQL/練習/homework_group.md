## 基礎聚合與分組
-- 1. 統計導演產量： 請計算每位 director_id 執導的電影總數，並將結果欄位命名為 movie_count。

-- 2. 導演的平均吸金能力： 請計算每位 director_id 所執導電影的 平均收入（revenue），並依平均收入由高到低排序。

-- 3. 預算最高紀錄： 請找出每位 director_id 執導過的電影中，最高預算（budget） 是多少？

-- 4. 評分總投票統計： 請統計每位 director_id 所有電影累積的 總投票數（vote_count）。

## 進階分組過濾 (HAVING)
-- 5. 篩選資深導演： 請找出執導電影數量 超過 15 部 的 director_id 及其電影總數。

-- 6. 高評價門檻： 請找出電影 平均評分（vote_average）大於 7.5 的 director_id，且該導演執導的電影必須超過 3 部。

-- 7. 票房達標大師： 請找出執導電影 總票房收入（revenue）超過 5 億 美元的 director_id。

## 綜合邏輯挑戰
-- 8. 熱門度與規模分析： 請計算每位 director_id 的電影 平均人氣值（popularity），但只統計那些「總投票數（vote_count）大於 1000」的分組。

-- 9. 年度電影產量（進階）： release_date 的格式為 YYYY-MM-DD。請嘗試擷取年份（可用 SUBSTR(release_date, 1, 4)），統計 每年各發行了幾部電影？

--10. 投資報酬率過濾： 請計算每位 director_id 的 總預算 與 總收入，並篩選出那些「總收入是總預算 2 倍以上」的導演 ID。