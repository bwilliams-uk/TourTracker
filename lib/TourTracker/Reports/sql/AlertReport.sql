SELECT
alerts.departure_id departure_id,
tour.name tour_name,
tour.url tour_url,
DATE_FORMAT(departure.start_date, "%d %b %Y") AS  start_date,
alerts.sync_date sync_date,
alerts.sync_days_ago sync_days_ago,
alerts.alert_type AS alert_type,
alerts.availability_change availability_change,
alerts.new_availability new_availability,
alerts.price_change price_change,
alerts.new_price new_price,
alerts.percentage percentage

FROM view_departure_alerts alerts

JOIN departure
ON alerts.departure_id=departure.id

JOIN tour
ON departure.tour_id = tour.id

-- Used to identify whether an alert is the latest of that alert_type for the departure ID:
LEFT JOIN view_departure_alerts_last_occurence v
ON v.departure_id=alerts.departure_id
AND v.last_occurence=alerts.sync_date
AND v.alert_type = alerts.alert_type

-- Only select watched departures
WHERE departure.watch = 1

-- Set Max alert age
AND alerts.sync_days_ago <= :maxAge

-- Don't show "AVAILABILITY INCREASED" Alerts unless previously sold out
AND NOT (alerts.alert_type = "AVAILABILITY INCREASED" AND new_availability > availability_change)

-- Set threshold for "AVAILABILY REDUCED" Alerts
AND NOT (alerts.alert_type = "AVAILABILITY REDUCED"  AND new_availability > :availabilityThreshold)

-- Don't show "AVAILABILITY INCREASED" Alerts unless previously below threshold
-- AND NOT (alerts.alert_type = "AVAILABILITY INCREASED"  AND new_availability - availability_change > :availabilityThreshold)

-- Only show the most recent Availability Increase/Decrease alerts
AND NOT (alerts.alert_type IN ("AVAILABILITY INCREASED", "AVAILABILITY REDUCED","SOLD OUT") AND v.last_occurence IS NULL)

-- Ignore Price reductions where new price is zero (indicating no availability)
AND NOT (alerts.alert_type = "PRICE REDUCED" AND alerts.new_price = 0)
-- For the same reason ignore price increases where previous price was zero
AND NOT (alerts.alert_type = "PRICE INCREASED" AND alerts.new_price = alerts.price_change)


 ORDER BY sync_date DESC
