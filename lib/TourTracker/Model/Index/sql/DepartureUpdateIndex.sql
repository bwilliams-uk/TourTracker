SELECT departure_update.id
FROM departure_update
-- [:tourId] INNER JOIN departure ON departure.id=departure_update.departure_id
-- [latest] INNER JOIN view_departure_latest_sync v ON v.departure_update_id = departure_update.id
WHERE 1=1
-- [:tourId] AND departure.tour_id=:tourId
-- [:departureId] AND departure_update.departure_id=:departureId
ORDER BY departure_update.created_at DESC
