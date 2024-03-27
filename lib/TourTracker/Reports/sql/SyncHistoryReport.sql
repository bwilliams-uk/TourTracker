SELECT vdsh.*,
        DATEDIFF(NOW(),sync_date) as days_ago
FROM view_departure_sync_history vdsh
-- NOTE: Bind :departureId with PHP
-- [:departureId] WHERE departure_id=:departureId
ORDER BY sync_date DESC
