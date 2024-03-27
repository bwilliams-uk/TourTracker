SELECT tour.id
FROM tour
-- [:syncLimit] JOIN view_tour_last_sync v ON v.id=tour.id
WHERE 1=1
-- PHP to uncomment as required
-- [:url] AND url = :url
-- [:syncLimit] AND TIMESTAMPDIFF(HOUR,v.last_sync,NOW()) >= :syncLimit
