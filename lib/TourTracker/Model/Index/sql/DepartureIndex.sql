SELECT id
FROM departure d
LEFT JOIN view_departure_latest_sync v ON  d.id=v.departure_id
WHERE
    (:tourId IS NULL OR d.tour_id=:tourId)
AND (:startDate IS NULL OR d.start_date=:startDate)
AND (:endDate IS NULL OR d.end_date=:endDate)
AND (:available IS NULL OR (:available = 1 AND  v.availability > 0) OR (:available = 0 AND v.availability < 1))
