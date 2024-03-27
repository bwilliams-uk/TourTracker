SELECT id
FROM departure d
-- [available] JOIN view_departure_latest_sync v ON d.id=v.departure_id
WHERE 1=1
-- PHP to uncomment as required
-- [:tourId]     AND d.tour_id=:tourId
-- [:startDate]  AND d.start_date=:startDate
-- [:endDate]    AND d.end_date=:endDate
-- [available]   AND v.availability > 0
