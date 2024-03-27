/*
Purpose:
To obtain Departure Update Ids
where criteria are met:

Available fields:
- :tourId
- :departureId -- get only
- :latest -- get only updates which are the latest for the departure

*/

SELECT departure_update.id
FROM departure_update
LEFT JOIN departure ON  departure.id=departure_update.departure_id
LEFT JOIN view_departure_latest_sync v ON v.departure_update_id = departure_update.id
WHERE
    (:tourId IS NULL OR departure.tour_id=:tourId)
AND (:departureId IS NULL OR departure_update.departure_id=:departureId)
AND (:latest IS NULL
     OR (:latest = 1 AND v.latest_sync IS NOT NULL)
     OR (:latest = 0 AND v.latest_sync IS NULL))
ORDER BY departure_update.created_at DESC
