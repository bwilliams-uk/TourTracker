SELECT
v.tour_id,
v.departure_id,
v.tour_name,
v.operator_id,
v.operator_name,
DATE_FORMAT(v.start_date,"%d %b %Y") AS start_date,
DATE_FORMAT(v.end_date,"%d %b %Y") AS end_date,
v.duration_days,
v.price,
v.availability,
v.sync_date,
v.sync_days_ago,
v.watch
FROM view_departure_sync_recent v
WHERE 1=1
-- NOTE: PHP will uncomment the following if filtering required:
-- [:tourId] AND v.tour_id = :tourId
-- [watch] AND v.watch=1
ORDER BY v.start_date ASC, v.tour_name ASC
