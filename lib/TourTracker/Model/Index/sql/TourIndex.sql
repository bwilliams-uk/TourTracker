SELECT tour.id
FROM tour
LEFT JOIN view_tour_last_sync v ON v.id=tour.id
WHERE
    (:url IS NULL OR url = :url)
AND (:syncDue IS NULL
     OR (:syncDue= 1 AND ((TIMESTAMPDIFF(HOUR,v.last_sync,NOW()) >= :syncLimit) OR v.last_sync IS NULL))
     OR (:syncDue= 0 AND TIMESTAMPDIFF(HOUR,v.last_sync,NOW()) < :syncLimit)
    )
