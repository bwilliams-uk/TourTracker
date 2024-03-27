SELECT
        t.id tour_id,
        t.name tour_name,
        t.url tour_url,
        o.id operator_id,
        o.name operator_name,
        vtls.last_sync last_sync,
        TIMESTAMPDIFF(HOUR,vtls.last_sync,NOW()) last_sync_hours_ago,
        -- NOTE must Bind :syncLimit with PHP:
        IF(TIMESTAMPDIFF(HOUR,vtls.last_sync,NOW()) >= :syncLimit
            OR vtls.last_sync IS NULL,true,false) sync_due
FROM tour t
JOIN tour_operator o
    ON t.operator_id=o.id
JOIN view_tour_last_sync vtls
    ON t.id = vtls.id
ORDER BY tour_name
